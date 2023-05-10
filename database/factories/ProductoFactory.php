<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Producto::class, function (Faker $faker) {
    return [
        'nombre' => $faker->sentence(),
        'id_marca' => $faker->randomElement([1,2,3,4,5,6,7]),
        'id_tipo_producto' => $faker->randomElement([1,2]),
        'es_de_venta' => $faker->randomElement([0,1]),
    ];
});
