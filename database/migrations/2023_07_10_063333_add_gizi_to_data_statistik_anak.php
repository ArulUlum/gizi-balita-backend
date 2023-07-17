<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGiziToDataStatistikAnak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_statistik_anak', function (Blueprint $table) {
            $table->float('z_score_gizi')->nullable();
            $table->string('status_gizi')->nullable();
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
            $table->dropColumn('z_score_gizi');
            $table->dropColumn('status_gizi');
        });
    }
}
