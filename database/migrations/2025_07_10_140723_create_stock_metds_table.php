<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMetdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_metd', function (Blueprint $table) {
            $table->id();
            $table->string('kode_brg_metd');
            $table->string('kode_brg_ph');
            $table->string('nama_brg_metd');
            $table->string('nama_brg_phapros');
            $table->string('plant');
            $table->string('nama_plant');
            $table->string('suhu_gudang_penyimpanan');
            $table->string('batch_phapros');
            $table->date('expired_date');
            $table->string('satuan_metd', 10);
            $table->string('satuan_phapros', 10);
            $table->integer('harga_beli');
            $table->integer('konversi_qty');
            $table->integer('qty_onhand_metd');
            $table->integer('qty_selleable');
            $table->integer('qty_non_selleable');
            $table->integer('qty_intransit_in');
            $table->bigInteger('nilai_intransit_in');
            $table->integer('qty_intransit_pass');
            $table->bigInteger('nilai_intransit_pass');
            $table->date('tgl_terima_brg');
            $table->string('source_beli');
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
        Schema::dropIfExists('stock_metd');
    }
}
