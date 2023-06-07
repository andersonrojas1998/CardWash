<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenta;
use App\Model\DetalleVentaProductos;
use App\Model\Producto;
use App\Model\TipoVehiculo;
use App\Model\users;
use App\Model\Venta;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::all();

        return view('venta.index', compact('ventas'));
    }

    public function create()
    {
        $tipos_vehiculo = TipoVehiculo::all();
        $date = Carbon::now();
        $date->setTimezone('America/Bogota');
        $productos = Producto::select(DB::raw("producto.*, (producto.cant_stock-producto.cant_stock_mov) AS cantidad"))->get()->where('cantidad', '>', 0);
        $usuarios = users::all();
        return view('venta.create', compact('tipos_vehiculo', 'date', 'productos', 'usuarios'));
    }

    public function store(StoreVenta $request)
    {
        try{
            $venta = new Venta($request->all());
            $venta->save();

            if(isset($request->all()['id_producto'])){
                foreach($request->all()['id_producto'] as $key => $id_producto){
                    $detalle_venta_producto = new DetalleVentaProductos([
                        "id_producto" => $id_producto,
                        "id_venta" => $venta->id,
                        "cantidad" => $request->all()['cantidad'][$key],
                        "precio_venta" => $request->all()['precio_venta'][$key],
                        "margen_ganancia" => $request->all()['margen_ganancia'][$key],
                    ]);
                    $detalle_venta_producto->save();
                    $producto = $detalle_venta_producto->producto;
                    $producto->cant_stock_mov = $producto->cant_stock_mov + $request->all()['cantidad'][$key];
                    $producto->save();
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
}
