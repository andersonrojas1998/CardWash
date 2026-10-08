<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKmFieldsToVentaTable extends Migration
{
    public function up()
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->integer('km_actual')->nullable()->after('numero_telefono');
            $table->integer('km_proximo_cambio')->nullable()->after('km_actual');
        });
    }

    public function down()
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->dropColumn(['km_actual', 'km_proximo_cambio']);
        });
    }
}
