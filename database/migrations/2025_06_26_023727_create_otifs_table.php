<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otifs', function (Blueprint $table) {
            $table->id();
            $table->string('produk');
            $table->integer('jumlah_pesanan');
            $table->integer('jumlah_terkirim');
            $table->date('tanggal_pesanan');
            $table->date('tanggal_kirim');
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
        Schema::dropIfExists('otifs');
    }
}
