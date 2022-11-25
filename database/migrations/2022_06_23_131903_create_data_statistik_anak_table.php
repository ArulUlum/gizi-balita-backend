<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataStatistikAnakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_statistik_anak', function (Blueprint $table) {
            $table->id();
            $table->float('tinggi')->nullable();
            $table->float('berat')->nullable();
            $table->float('lingkar_kepala')->nullable();
            $table->date('date')->nullable();
            $table->foreignId('id_anak')->nullable()->constrained('data_anak', 'id');
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
        Schema::dropIfExists('data_statistik_anak');
    }
}
