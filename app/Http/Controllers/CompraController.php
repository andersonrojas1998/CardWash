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
        $compras = Compra::all();
        $data = [
            "status" => 200,
            "data" => []
        ];

        foreach ($compras as $compra){
            $compra->estado = $compra->estado();

            $compra->condicion = $compra->condiciones();

            $compra->route_products = route('compra.products', ['id_compra' => $compra->id]);

            array_push($data['data'], $compra);
        }

        return response()->json($data);
    }
    
    public function store(StoreCompra $request)
    {
        try{
            $detalle_compra_productos = [
                'id_producto' => $request->all()['id_producto'],
                'id_unidad_de_medida' => $request->all()['id_unidad_de_medida'],
                'cantidad_producto' => $request->all()['cantidad'],
                'precio_compra_producto' => $request->all()['precio_compra'],
                'precio_venta_producto' => $request->all()['precio_venta']
            ];
            $compra = new Compra($request->all());
            $compra->save();

            foreach($detalle_compra_productos['id_producto'] as $key => $id_producto){
                $detalle_compra_producto = new DetalleCompraProductos();
                $detalle_compra_producto->id_producto = $id_producto;
                $detalle_compra_producto->id_unidad_de_medida = $detalle_compra_productos['id_unidad_de_medida'][$key];
                $detalle_compra_producto->cantidad = $detalle_compra_productos['cantidad_producto'][$key];
                $detalle_compra_producto->precio_compra = $detalle_compra_productos['precio_compra_producto'][$key];
                $detalle_compra_producto->precio_venta = $detalle_compra_productos['precio_venta_producto'][$key];
                $detalle_compra_producto->id_compra = $compra->id;
                $detalle_compra_producto->save();
            }

            return redirect()->route('compra.index', [], 201)->with('success', 'Se ha creado la compra satisfactoriamente');
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function update(StoreCompra $request)
    {
        $compra = Compra::find($request->input('id'));
        $compra->update($request->all());

        foreach ($request->all()['id_detalle_compra_producto'] as $key => $id_detalle_compra_producto) {
            $values = [
                "id_producto" => $request->all()['id_producto'][$key],
                'id_compra' => $compra->id,
                'id_unidad_de_medida' => $request->all()['id_unidad_de_medida'][$key],
                'cantidad' => $request->all()['cantidad'][$key],
                'precio_compra' => $request->all()['precio_compra'][$key],
                'precio_venta' => $request->all()['precio_venta'][$key],
            ];
            DetalleCompraProductos::where('id', $id_detalle_compra_producto)->update($values);
        }

        return redirect()->route('compra.index')->with('success', 'Se ha modificado la compra satisfactoriamente.');
    }

    public function destroy(Compra $compra)
    {
        $compra->delete();
        return redirect()->route('compra.index')->with('success', 'Se ha eliminado la compra satisfactoriamente.');
    }

    public function getProducts(Compra $compra)
    {
        $data = [];
        $detalleCompraProductos = DetalleCompraProductos::where('id_compra', $compra->id)->get();
        foreach($detalleCompraProductos as $key => $detalle_compra_producto){
            $detalle_compra_producto->producto = $detalle_compra_producto->producto();
            $detalle_compra_producto->producto->tipo_producto = $detalle_compra_producto->producto->tipo_producto();
            $detalle_compra_producto->unidad_de_medida = $detalle_compra_producto->unidad_de_medida();
            $data[$key] = $detalle_compra_producto;
        }
        return response()->json($data);
    }
}
