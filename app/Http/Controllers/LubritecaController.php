<?php

namespace App\Http\Controllers;

use App\Model\DetalleVentaProductos;
use App\Model\Venta;
use App\Model\users;
use App\Services\StockService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LubritecaController extends Controller
{
    public function index()
    {
        $usuarios = users::select("users.*")
            ->join("roles as r", "cargo", "r.id")
            ->whereIn("r.slug", ["Lavador", "Tienda"])
            ->get();

        return view('lubriteca.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente'  => 'required|string|max:80',
            'numero_telefono' => 'required',
            'id_usuario'      => 'required|integer',
            'productos'       => 'required|array|min:1',
            'total_venta'     => 'required|numeric|min:0',
        ]);

        try {
            $venta = DB::transaction(function () use ($request) {
                $venta = new Venta();
                $venta->nombre_cliente    = strtoupper($request->nombre_cliente);
                $venta->placa             = strtoupper($request->placa ?? '');
                $venta->numero_telefono   = $request->numero_telefono;
                $venta->km_actual         = $request->km_actual ?: null;
                $venta->km_proximo_cambio = $request->km_proximo_cambio ?: null;
                $venta->id_usuario        = $request->id_usuario;
                $venta->id_estado_venta   = 3;
                $venta->fecha             = date('Y-m-d H:i:s');
                $venta->total_venta       = floatval($request->total_venta);
                $venta->precio_venta_paquete = 0;
                $venta->porcentaje_paquete   = 0;
                $venta->save();

                foreach ($request->productos as $item) {
                    StockService::descontar(
                        $venta->id,
                        intval($item['id_producto']),
                        intval($item['cantidad']),
                        floatval($item['precio_venta'])
                    );
                }

                return $venta;
            });

            return response()->json(['id' => $venta->id, 'success' => true]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sticker($id)
    {
        $venta    = Venta::find($id);
        $productos = DB::SELECT("CALL sp_groupSalesProduct('$venta->id')");

        return view('lubriteca.sticker', compact('venta', 'productos'));
    }

    public function scanner()
    {
        return view('lubriteca.scanner');
    }
}
