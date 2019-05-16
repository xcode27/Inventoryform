<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePocreatedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pocreateds', function (Blueprint $table) {
            $table->increments('id');
            $table->char('po_no',25);
            $table->char('store',50);
            $table->char('store_name',120);
            $table->date('po_date');
            $table->char('received_by',25);
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
        Schema::dropIfExists('pocreateds');
    }
}
