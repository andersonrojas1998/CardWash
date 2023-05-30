<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompra;
use App\Model\Compra;
use App\Model\DetalleCompraProductos;
use Exception;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function index(){
        return view('compra.index');
    }

    public function dataTable()
    {
        $compras = Compra::get();
        $data = [
            "status" => 200,
            "data" => []
        ];

        foreach ($compras as $compra){
            $compra->estado;

            $compra->condicion;

            $compra->route_edit = route('compra.edit', ['compra' => $compra->id]);

            array_push($data['data'], $compra);
        }

        return response()->json($data);
    }

    public function create()
    {
        return view('compra.create');
    }
    
    public function store(StoreCompra $request)
    {
        try{
            $compra = new Compra($request->all());
            $compra->save();

            $detalle_compra_productos = [];

            foreach($request->all()['id_producto'] as $key => $id_producto){
                $detalle_compra_productos[$key] = [
                'id_producto' => $id_producto,
                'id_unidad_de_medida' => $request->all()['id_unidad_de_medida'][$key],
                'cantidad' => $request->all()['cantidad'][$key],
                'precio_compra' => $request->all()['precio_compra'][$key],
                'precio_venta' => $request->all()['precio_venta'][$key],
                'id_compra' => $compra->id
                ];
            }

            foreach($detalle_compra_productos as $key => $detalle_compra_producto){
                $detalle_compra_producto = new DetalleCompraProductos($detalle_compra_producto);
                $detalle_compra_producto->save();
            }

            return redirect()->route('compra.index')->with('success', 'Se ha creado la compra satisfactoriamente');
        }catch(Exception $e){
            return redirect()->route('compra.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function edit(Compra $compra)
    {
        return view('compra.edit', compact('compra'));
    }

    public function update(StoreCompra $request)
    {
        try{
            $compra = Compra::find($request->input('id'));
            $compra->update($request->all());
            
            foreach ($request->all()['id_detalle_compra_producto'] as $key => $id_detalle_compra_producto) {
                $detalle_compra_producto = $compra->detalle_compra_productos->find($id_detalle_compra_producto);
                $values = [
                    "id_producto" => $request->all()['id_producto'][$key],
                    'id_compra' => $compra->id,
                    'id_unidad_de_medida' => $request->all()['id_unidad_de_medida'][$key],
                    'cantidad' => $request->all()['cantidad'][$key],
                    'precio_compra' => $request->all()['precio_compra'][$key],
                    'precio_venta' => $request->all()['precio_venta'][$key],
                ];
                if(!empty($detalle_compra_producto)){
                    $detalle_compra_producto->update($values);
                }else{
                    $detalle_compra_producto = new DetalleCompraProductos($values);
                    $detalle_compra_producto->save();
                }
            }

            return redirect()->route('compra.index')->with('success', 'Se ha modificado la compra satisfactoriamente.');
        }catch(Exception $e){
            return redirect()->route('compra.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function destroy(Compra $compra)
    {
        $compra->delete();
        return redirect()->route('compra.index')->with('success', 'Se ha eliminado la compra satisfactoriamente.');
    }

    public function getProducts(Compra $compra)
    {
        $data = [];
        foreach($compra->detalle_compra_productos as $key => $detalle_compra_producto){
            $detalle_compra_producto->producto;
            $detalle_compra_producto->producto->marca;
            $detalle_compra_producto->producto->tipo_producto;
            $detalle_compra_producto->unidad_de_medida;
            $data[$key] = $detalle_compra_producto;
        }
        return response()->json($data);
    }
}
