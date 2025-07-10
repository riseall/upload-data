<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_brg_metd')->unique();
            $table->string('kode_brg_ph');
            $table->string('nama_brg_metd');
            $table->string('nama_brg_ph');
            $table->string('satuan_metd', 10);
            $table->string('satuan_ph', 10);
            $table->integer('konversi_qty');
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
        Schema::dropIfExists('master_products');
    }
}
