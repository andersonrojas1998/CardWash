<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompra;
use App\Model\Compra;
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

            array_push($data['data'], $compra);
        }

        return response()->json($data);
    }
    
    public function store(StoreCompra $request)
    {
        try{
            $compra = new Compra($request->all());
            $compra->save();

            return redirect()->route('compra.index', [], 201)->with('success', 'Se ha creado la compra satisfactoriamente');
        }catch(Exception $e){
            return response()->json($e->getMessage(), 500);
        }
    }

    public function update(StoreCompra $request)
    {
        $compra = Compra::find($request->input('id'));
        $compra->update($request->all());

        return redirect()->route('compra.index')->with('success', 'Se ha modificado la compra satisfactoriamente.');
    }

    public function destroy(Compra $compra)
    {
        $compra->delete();
        return redirect()->route('compra.index')->with('success', 'Se ha eliminado la compra satisfactoriamente.');
    }
}
