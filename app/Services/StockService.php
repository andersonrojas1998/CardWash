<?php

namespace App\Services;

use App\Model\DetalleVentaProductos;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Manejo de inventario por lotes (cada fila de detalle_compra_productos es un lote).
 * Disponible de un lote = cantidad comprada - cantidad vendida de ese lote.
 */
class StockService
{
    /**
     * Lotes con existencia de un producto, del más antiguo al más nuevo (FIFO).
     * Bloquea las filas de los lotes para evitar sobreventa en ventas simultáneas.
     * Debe llamarse dentro de DB::transaction().
     */
    public static function lotesDisponibles($idProducto)
    {
        $lotes = DB::table('detalle_compra_productos as dcp')
            ->select('dcp.id_detalle_compra', 'dcp.precio_compra', 'dcp.cantidad')
            ->where('dcp.id_producto', intval($idProducto))
            ->orderBy('dcp.id_detalle_compra')
            ->lockForUpdate()
            ->get();

        $vendidos = DB::table('detalle_venta_productos')
            ->select('id_detalle_producto', DB::raw('SUM(cantidad) as vendido'))
            ->whereIn('id_detalle_producto', $lotes->pluck('id_detalle_compra'))
            ->groupBy('id_detalle_producto')
            ->pluck('vendido', 'id_detalle_producto');

        return $lotes->map(function ($l) use ($vendidos) {
            $l->restante = intval($l->cantidad) - intval($vendidos[$l->id_detalle_compra] ?? 0);
            return $l;
        })->filter(function ($l) {
            return $l->restante > 0;
        })->values();
    }

    /**
     * Descuenta $cantidad unidades de un producto repartiéndolas entre lotes (FIFO)
     * y crea las filas de detalle_venta_productos. Lanza excepción si no hay stock suficiente.
     */
    public static function descontar($idVenta, $idProducto, $cantidad, $precioVenta)
    {
        $pendiente = intval($cantidad);
        if ($pendiente <= 0) {
            return;
        }

        $lotes = self::lotesDisponibles($idProducto);
        $disponible = $lotes->sum('restante');

        if ($disponible < $pendiente) {
            $nombre = DB::table('producto')->where('id', $idProducto)->value('nombre');
            throw new Exception("Stock insuficiente para {$nombre}: disponible {$disponible}, solicitado {$pendiente}.");
        }

        foreach ($lotes as $lote) {
            if ($pendiente <= 0) {
                break;
            }
            $tomar = min($pendiente, $lote->restante);

            DetalleVentaProductos::create([
                'id_detalle_producto' => $lote->id_detalle_compra,
                'id_venta'            => $idVenta,
                'cantidad'            => $tomar,
                'precio_venta'        => $precioVenta,
                'margen_ganancia'     => $precioVenta - floatval($lote->precio_compra),
            ]);

            $pendiente -= $tomar;
        }
    }
}
