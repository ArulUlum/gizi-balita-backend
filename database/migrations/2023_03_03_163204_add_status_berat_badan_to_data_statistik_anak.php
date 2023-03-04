<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusBeratBadanToDataStatistikAnak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_statistik_anak', function (Blueprint $table) {
            $table->string('status_berat_badan')->nullable();
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
            $table->dropColumn('status_berat_badan');
        });
    }
}
