<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Producto::class, function (Faker $faker) {
    return [
        'nombre' => $faker->sentence(),
        'id_marca' => $faker->randomElement([1,2,3,4,5,6,7]),
        'id_tipo_producto' => $faker->randomElement([1,2]),
        'id_unidad_de_medida' => $faker->randomElement([1,3,5]),
    ];
});
