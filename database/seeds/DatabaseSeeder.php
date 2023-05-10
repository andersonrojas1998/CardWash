<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(TipoProductoSeeder::class);
        $this->call(ProductoSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(CondicionesSeeder::class);
        $this->call(CompraSeeder::class);
        $this->call(UnidadDeMedidaSeeder::class);
    }
}
