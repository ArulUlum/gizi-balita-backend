<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatistikTerakhirAnakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_anak', function (Blueprint $table) {
            $table->string('berat_terakhir')->nullable();
            $table->string('tinggi_terakhir')->nullable();
            $table->string('lingkar_kepala_terakhir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_anak', function (Blueprint $table) {
            $table->dropColumn('berat_terakhir');
            $table->dropColumn('tinggi_terakhir');
            $table->dropColumn('lingkar_kepala_terakhir');
        });
    }
}
