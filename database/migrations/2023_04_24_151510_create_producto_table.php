<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->increments('id')->nullable(false);
            $table->string('nombre')->default(null);
            $table->integer('id_marca')->nullable(false)->unsigned()->index();
            $table->foreign('id_marca')->references('id')->on('marca')->onDelete('cascade');
            $table->integer('id_tipo_producto')->nullable(false)->unsigned()->index();
            $table->foreign('id_tipo_producto')->references('id')->on('tipo_producto')->onDelete('cascade');
            $table->integer('id_unidad_de_medida')->nullable(false)->unsigned()->index();
            $table->foreign('id_unidad_de_medida')->references('id')->on('unidad_de_medida')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto');
    }
}
