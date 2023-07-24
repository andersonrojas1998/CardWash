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
        $productos = DetalleCompraProductos::select(DB::raw("*, cast(if((detalle_compra_productos.cantidad-(SELECT sum(cantidad) FROM detalle_venta_productos AS dvp WHERE detalle_compra_productos.id_detalle_compra = dvp.id_detalle_producto)) is null, detalle_compra_productos.cantidad, (detalle_compra_productos.cantidad-(SELECT sum(cantidad) FROM detalle_venta_productos AS dvp WHERE detalle_compra_productos.id_detalle_compra = dvp.id_detalle_producto))) AS unsigned) cantidad_disponible"))->join("producto as p", "p.id", "detalle_compra_productos.id_producto")->where("id_area","!=","3")->get()->where('cantidad_disponible', '>', 0);
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
            $venta = new Venta($request->all());
            $venta->fecha=date('Y-m-d h:i:s');
            $venta->save();

            // *** id_producto = id_detalle_compra ***

            if(isset($request->all()['id_producto'])){
                foreach($request->all()['id_producto'] as $key => $id_producto){
                    $detalle_venta_producto = new DetalleVentaProductos([
                        "id_detalle_producto" => $id_producto,
                        "id_venta" => $venta->id,
                        "cantidad" => $request->all()['cantidad'][$key],
                        "precio_venta" => $request->all()['precio_venta'][$key],
                        "margen_ganancia" => $request->all()['margen_ganancia'][$key],
                    ]);
                    $detalle_venta_producto->save();
                }
            }

            return redirect()->route('venta.show', [$venta->id])->with('success', 'Se ha registrado la venta satisfactoriamente');
        }catch(Exception $e){
            return redirect()->route('venta.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function edit()
    {
        $tipos_vehiculo = TipoVehiculo::all();
        return view('venta.edit', compact('tipos_vehiculo'));
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
        return view("venta.show", compact('venta'));
    }
    public function showCopy($ventas)
    {
        $venta = Venta::find($ventas);

        return view("venta.showCopy", compact('venta'));
    }
}
