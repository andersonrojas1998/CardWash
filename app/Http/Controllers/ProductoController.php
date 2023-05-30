<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProducto;
use App\Model\Producto;
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
            $c=0;
            foreach($producto->compras as $cant){
                $c+=$cant->cantidad;                
            }
            $producto->marca = $producto->marca->nombre;
            $producto->tipo_producto = $producto->tipo_producto();
            $producto->cantidad = $c;

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
