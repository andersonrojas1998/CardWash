<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetallePaquete;
use App\Model\DetallePaquete;
use App\Model\Paquete;
use App\Model\Servicio;
use App\Model\ServicioPaquete;
use App\Model\TipoVehiculo;
use Exception;

class DetallePaqueteController extends Controller
{
    public function index()
    {
        $paquetes_carro = Paquete::select('paquete.*')->join(
            'detalle_paquete AS dp',
            'dp.id_paquete',
            'paquete.id')
            ->join(
                'tipo_vehiculo AS tv',
                'tv.id',
                'dp.id_tipo_vehiculo')
            ->where(
                'tv.nomenclatura',
                'C')
            ->orderBy('paquete.nombre')
            ->groupBy('paquete.id')
            ->paginate(4);

        $paquetes_moto = Paquete::select('paquete.*')->join(
            'detalle_paquete AS dp',
            'dp.id_paquete',
            'paquete.id')
            ->join(
                'tipo_vehiculo AS tv',
                'tv.id',
                'dp.id_tipo_vehiculo')
            ->where(
                'tv.nomenclatura',
                'M')
            ->orderBy('paquete.nombre')
            ->groupBy('paquete.id')
            ->paginate('4');

        return view('detalle-paquete.index', compact('paquetes_carro', 'paquetes_moto'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        $paquetes_sin_parametrizar = Paquete::select('paquete.*')
        ->crossJoin(
            'tipo_vehiculo AS tv')
        ->leftJoin(
            'detalle_paquete AS dp',
            [
                ['tv.id','dp.id_tipo_vehiculo'],
                ['dp.id_paquete','paquete.id']
            ])
            ->whereNull('dp.id')
            ->groupBy('paquete.id')
            ->get();

        return view('detalle-paquete.create', compact('paquetes_sin_parametrizar', 'servicios'));
    }

    public function store(StoreDetallePaquete $request)
    {
        try{
            $input = $request->all();
            
            $detalle_paquete = new DetallePaquete($input);
            $detalle_paquete->save();

            foreach($input['id_servicio'] as $key => $id_servicio){
                $servicio = new ServicioPaquete([
                    'id_servicio' => $id_servicio,
                    "id_paquete" => $detalle_paquete->id
                ]);
                $servicio->save();
            }

            return redirect()->route('detalle-paquete.index')->with('success', 'Se ha creado el combo/servicio ' . $detalle_paquete->paquete->nombre . ' satisfactoriamente.');
        }catch(Exception $e){
            return redirect()->route('detalle-paquete.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function edit(Paquete $paquete)
    {
        return view('paquete.edit', compact('paquete'));
    }

    public function update(StoreDetallePaquete $request, DetallePaquete $detalle_paquete)
    {
        try{
            $input = $request->all();
            //$paquete->update($input);

            $detalles_paquete = [];

            foreach($input['id_tipo_vehiculo'] as $key => $id_tipo_vehiculo){
                $detalles_paquete[$key] = [
                    'id_detalle_paquete' => $input['id_detalle_paquete'][$key],
                    'precio_venta' => $input['precio_venta'][$key],
                    'porcentaje' => ($input['porcentaje'][$key] == 'null')? null : $input['porcentaje'][$key],
                    'id_tipo_vehiculo' => $id_tipo_vehiculo,
                    'id_paquete' => $input['id_paquete'][$key]
                ];

                foreach($input['id_servicio'][$key] as $key2 => $id_servicio){
                    $detalles_paquete[$key]['servicios'][$key2] = [
                        'id_servicio_paquete' => ($input['id_servicio_paquete'][$key][$key2] == 'null')? null : $input['id_servicio_paquete'][$key][$key2],
                        'id_servicio' => ($id_servicio == 'null')? null : $id_servicio,
                        'id_paquete' => $input['id_detalle_paquete'][$key]
                    ];
                }
            }

            /*foreach($paquete->detalle_paquete as $detalle_paquete_input){
                $detalle_paquete = DetallePaquete::find($detalle_paquete_input['id_detalle_paquete']);
                $detalle_paquete->update($detalle_paquete);

                foreach($detalle_paquete_input['servicios'] as $servicio_paquete_input){
                    if($servicio_paquete_input['id_servicio_paquete'] != null){
                        $servicio_paquete = ServicioPaquete::find($servicio_paquete_input['id_servicio_paquete']);
                        $servicio_paquete->update($servicio_paquete_input);
                    }
                }
            }*/

            redirect()->route('paquete.index')->with('success', 'Se ha modificado el combo/servicio ' . $detalle_paquete->paquete->nombre . ' satisfactoriamente.');
        }catch(Exception $e){
            redirect()->route('paquete.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function unrelatedVehicleType(Paquete $paquete)
    {
        $tipos_vehiculo = TipoVehiculo::select('tipo_vehiculo.*')
        ->crossJoin('paquete AS p')
        ->leftJoin(
            'detalle_paquete AS dp',
            [
                ['dp.id_tipo_vehiculo', 'tipo_vehiculo.id'],
                ['p.id', 'dp.id_paquete']
            ])
        ->whereNull('dp.id')
        ->where('p.id', $paquete->id)
        ->get();

        $data = [
            "status" => "200",
            "tipos_vehiculo" => []
        ];

        foreach ($tipos_vehiculo as $tipo_vehiculo ) {
            $tipo_vehiculo->img_url = $tipo_vehiculo->imagen;
            array_push($data['tipos_vehiculo'], $tipo_vehiculo);
        }

        return response()->json($data);
    }
}
