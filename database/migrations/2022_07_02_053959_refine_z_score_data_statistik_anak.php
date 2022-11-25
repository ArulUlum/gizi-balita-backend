<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefineZScoreDataStatistikAnak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_statistik_anak', function (Blueprint $table) {
            $table->renameColumn('z_score', 'z_score_tinggi');
            $table->float('z_score_berat')->nullable();
            $table->float('z_score_lingkar_kepala')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_statistik_anak', function (Blueprint $table) {
            $table->renameColumn('z_score_tinggi', 'z_score');
            $table->dropColumn('z_score_berat');
            $table->dropColumn('z_score_lingkar_kepala');
        });
    }
}
