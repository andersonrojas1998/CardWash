<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenta;
use App\Model\DetalleCompraProductos;
use App\Model\DetalleVentaProductos;
use App\Model\Producto;
use App\Model\TipoVehiculo;
use App\Model\users;
use App\Model\Venta;
use Carbon\Carbon;
use Exception;
use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::all();
        $usuarios = users::select("users.*")->join("roles as r", "cargo", "r.id")->where("r.slug", "Lavador")->get();
        return view('venta.index', compact('ventas','usuarios'));
    }

    public function create()
    {
        $tipos_vehiculo = TipoVehiculo::all();
        $date = Carbon::now();
        $date->setTimezone('America/Bogota');
        $productos=DB::SELECT("CALL sp_products('1,2')  ");
        $usuarios = users::select("users.*")->join("roles as r", "cargo", "r.id")->where("r.slug", "Lavador")->get();
        return view('venta.create', compact('tipos_vehiculo', 'date', 'productos', 'usuarios'));
    }

    public function createMarket()
    {
        $date = Carbon::now();
        $date->setTimezone('America/Bogota');
        $productos = DetalleCompraProductos::select(DB::raw("*, cast(if((detalle_compra_productos.cantidad-(SELECT sum(cantidad) FROM detalle_venta_productos AS dvp WHERE detalle_compra_productos.id_detalle_compra = dvp.id_detalle_producto)) is null, detalle_compra_productos.cantidad, (detalle_compra_productos.cantidad-(SELECT sum(cantidad) FROM detalle_venta_productos AS dvp WHERE detalle_compra_productos.id_detalle_compra = dvp.id_detalle_producto))) AS unsigned) cantidad_disponible"))->join("producto as p", "p.id", "detalle_compra_productos.id_producto")->where("id_area","3")->get()->where('cantidad_disponible', '>', 0);
        $usuarios = users::select("users.*")->join("roles as r", "cargo", "r.id")->where("r.slug", "Tienda")->get();
        return view('venta.create_market', compact('date', 'productos', 'usuarios'));
    }

    public function store(StoreVenta $request)
    {
        try{   
                     
            $status=1; 
            
            if(!isset($request->all()['id_producto'])   &&    !isset($request->all()['id_detalle_paquete']) ){
                return redirect()->back()->with('fail', 'Por favor diligenciar los campos correspondientes.');
            }
            if(isset($request->all()['id_producto'])   &&    !isset($request->all()['id_detalle_paquete']) ){
                $status=3;
            }
            $venta = new Venta($request->all());
            $venta->fecha=date('Y-m-d h:i:s');
            $venta->id_estado_venta=$status;
            $venta->total_venta= floatval($request->all()['importe_total']);
            $venta->precio_venta_paquete= (isset($request->all()['precio_venta_paquete']))? floatval($request->all()['precio_venta_paquete']):0;            
            $venta->porcentaje_paquete= (isset($request->all()['porcentaje_paquete']))?  intval($request->all()['porcentaje_paquete']):0;
            $venta->save();
           
            if(isset($request->all()['id_producto'])){

                //dd($request->all()['id_producto']);
                foreach($request->all()['id_producto'] as $key => $id_producto){
                  
            
                    $prd=intval($id_producto);
                    $stock=DB::SELECT("CALL sp_sell_prd_stock('$prd')  "); // consultar productos  disponibilidad order by DESC cantidad

               
                    $solicitado=intval($request->all()['cantidad'][$key]);   
                    $pricesale=floatval($request->all()['precio_venta'][$key]);                
                    $cantidadbd=0;                                        
                    foreach($stock as $i=> $st){


                     $cantidadbd+=intval($st->restante);
                     $op=$solicitado-$cantidadbd;
                        
                        if($op< 0){   // cantidad cuando exiten mayor en BD  --  negativo


                                $detalle_venta_producto = new DetalleVentaProductos([
                                    "id_detalle_producto" => $st->id_detalle_compra, // $id_producto,
                                     "id_venta" => $venta->id,
                                     "cantidad" => $solicitado, //  $request->all()['cantidad'][$key],
                                     "precio_venta" => $pricesale,
                                     "margen_ganancia" => $pricesale - $st->precio_compra,
                                 ]);
                                 $detalle_venta_producto->save();

                                break;
                        }elseif($op > 0 ){ // cantidad de solicitado es mayor 
                            // postivo 

                            // se coloca el de la bd 
                            $solicitado-=$cantidadbd;
                            
                             $detalle_venta_producto = new DetalleVentaProductos([
                               "id_detalle_producto" => $st->id_detalle_compra, // $id_producto,
                                "id_venta" => $venta->id,
                                "cantidad" => $cantidadbd, //  $request->all()['cantidad'][$key],
                                "precio_venta" =>$pricesale,
                                "margen_ganancia" => $pricesale - $st->precio_compra,
                            ]);
                            $detalle_venta_producto->save();

                        }else if ($op==0){


                            $detalle_venta_producto = new DetalleVentaProductos([
                                "id_detalle_producto" => $st->id_detalle_compra, // $id_producto,
                                 "id_venta" => $venta->id,
                                 "cantidad" => $cantidadbd, //  $request->all()['cantidad'][$key],
                                 "precio_venta" => $pricesale,
                                 "margen_ganancia" => $pricesale - $st->precio_compra,
                             ]);
                             $detalle_venta_producto->save();
                        }
                    }
                }                
            }

            return redirect()->route('venta.show', [$venta->id])->with('success', 'Se ha registrado la venta satisfactoriamente');
        }catch(Exception $e){
            return redirect()->route('venta.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function edit($id_venta)
    {
        $tipos_vehiculo = TipoVehiculo::all();
        $venta=Venta::find($id_venta);
        return view('venta.edit', compact('tipos_vehiculo','venta'));
    }


    public function updateUser(){
       
            DB::table('venta')
            ->where('id', intval(Request::input('id_venta')))            
            ->update([
                'id_usuario' =>intval(Request::input('id_user'))                         
                ]
        );
        return 1;



    }


    public function update(StoreVenta $request, Venta $venta)
    {
        try{
            dd($request);
            $venta->update($request->all());

            return redirect()->route('compra.index')->with('success', 'Se ha modificado la venta satisfactoriamente');
        }catch(Exception $e){
            return redirect()->route('compra.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function show(Venta $venta)
    {
        
        $productos=DB::SELECT("CALL sp_groupSalesProduct('$venta->id')  ");         
        return view("venta.show", compact('venta','productos'));
    }
    public function showCopy($ventas)
    {
        $venta = Venta::find($ventas);
        $productos=DB::SELECT("CALL sp_groupSalesProduct('$venta->id')  ");         

        return view("venta.showCopy", compact('venta','productos'));
    }
}
