<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProducto;
use App\Model\Producto;
use App\Model\Tipo_Producto;
use Exception;

class ProductoController extends Controller
{
    public function index(){
        return view('producto.index');
    }

    public function dataTable(){
        $productos = Producto::all();
        $data = [
            "status" => "200",
            "data" => []
        ];

        foreach ($productos as $producto ) {
            $producto->marca;
            $producto->tipo_producto;
            $producto->presentacion;
            $producto->unidad_medida;
            $producto->cantidad = $producto->cant_stock - $producto->cant_stock_mov;

            array_push($data['data'], $producto);
        }

        return response()->json($data);
    }

    public function create(){
        $html = view('producto.create')->render();
        return $html;
    }

    public function store(StoreProducto $request){
        try{
          
            $producto = new Producto($request->all());
            $producto->cant_stock = 0;
            $producto->cant_stock_mov = 0;
            $producto->save();

            return redirect()->route('producto.index')->with('success', 'Se ha creado el producto "' . $producto->nombre . '" satisfactoriamente.');
        }catch(Exception $e){
            return redirect()->route('producto.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function edit(Producto $producto){
        $html = view('producto.edit', compact('producto'));
        return $html;
    }

    public function update(StoreProducto $request){
        try{
            $producto = Producto::find($request->input('id'));
            $producto->update($request->all());

            return redirect()->route('producto.index')->with('success', 'Se ha modificado el producto "' . $producto->nombre . '" satisfactoriamente.');
        }catch(Exception $e){
            return redirect()->route('producto.index')->with('fail', 'Ha ocurrido un error al guardar<br><br>' . $e->getMessage());
        }
    }

    public function destroy(Producto $producto){
        $producto->delete();
        return redirect()->route('producto.index')->with('success', 'Se ha eliminado el producto satisfactoriamente.');
    }

    public function getQuantity(Producto $producto)
    {
        return response()->json($producto->cantidad());
    }
}
