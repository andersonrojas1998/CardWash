<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreProducto;
use App\Model\Producto;
use App\Model\Tipo_Producto;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(){
        return view('producto.index');
    }

    public function dataTable($area){

        $products=[];
       if(intval($area) == -1){
             $products=DB::SELECT("CALL sp_products_all()  ");
        }else{
            $products=DB::SELECT("CALL sp_products('$area')  ");
        }

        $imagenes = Producto::pluck('imagen', 'id');

       $data = [
            "status" => "200",
            "data" => []
        ];
        foreach ($products as $producto ) {
            $producto->presentacion;
            $producto->tipo_producto;
            $producto->marca;
            $producto->unidad_medida;
            $imagenPath = $imagenes[$producto->id] ?? null;
            // Ignorar el placeholder legacy; solo mostrar imágenes reales subidas
            if ($imagenPath && $imagenPath[0] !== '/') {
                $producto->imagen = asset('storage/' . $imagenPath);
            } else {
                $producto->imagen = null;
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
            $producto = new Producto($request->except('imagen'));
            if ($request->hasFile('imagen')) {
                $producto->imagen = $request->file('imagen')->store('productos', 'public');
            }
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
            $data = $request->except(['imagen', 'id']);
            if ($request->hasFile('imagen')) {
                // Solo eliminar si es ruta de storage (no rutas legacy /images/...)
                if ($producto->imagen && $producto->imagen[0] !== '/') {
                    Storage::disk('public')->delete($producto->imagen);
                }
                $data['imagen'] = $request->file('imagen')->store('productos', 'public');
            }
            $producto->update($data);

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
