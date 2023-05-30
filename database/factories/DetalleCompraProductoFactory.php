<?php

use App\Model\Producto;
use App\Model\UnidadDeMedida;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

$factory->define(App\Model\DetalleCompraProductos::class, function (Faker $faker) {
    $precio_compra = $faker->randomNumber(4);
    return [
        'id_producto' => Producto::all()->random()->id,
        'id_compra' => DB::table('compra')
                        ->leftJoin('detalle_compra_productos', 'detalle_compra_productos.id_compra', '=', 'compra.id')
                        ->whereNull('detalle_compra_productos.id_compra')
                        ->value('compra.id'),
        'id_unidad_de_medida' => UnidadDeMedida::all()->random()->id,
        'cantidad' => $faker->randomNumber(3),
        'precio_compra' => $precio_compra,
        'precio_venta' => $precio_compra + 20000,
    ];
});
