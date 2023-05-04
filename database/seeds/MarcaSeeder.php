<?php

use App\Model\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $marca = new Marca([
            'nombre' => 'Test'
        ]);
        $marca->save();
    }
}
