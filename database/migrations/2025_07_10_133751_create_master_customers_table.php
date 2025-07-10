<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_customers', function (Blueprint $table) {
            $table->id();
            $table->string('id_outlet')->unique();
            $table->string('nama_outlet');
            $table->string('cbg_ph');
            $table->string('kode_cbg_ph');
            $table->string('cbg_metd');
            $table->string('alamat_1');
            $table->string('alamat_2');
            $table->string('alamat_3')->nullable();
            $table->string('no_telp', 20)->nullable();
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
        Schema::dropIfExists('master_customers');
    }
}
