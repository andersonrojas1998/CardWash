<?php

namespace App\Http\Controllers;
use DB;
use App\Http\Requests\StoreProducto;
use App\Model\Producto;
use App\Model\Tipo_Producto;
use Exception;

class ProductoController extends Controller
{
    public function index(){
        return view('producto.index');
    }

    public function dataTable($area){
  
        $products=[];
       if(intval($area) == -1){
            $products=Producto::all();
        }else{
            $products=DB::SELECT("CALL sp_products('$area')  ");
        }
       $data = [
            "status" => "200",
            "data" => []
        ];
        foreach ($products as $producto ) {

            if(intval($area) == -1){
                $producto->presentacion;
            }
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
