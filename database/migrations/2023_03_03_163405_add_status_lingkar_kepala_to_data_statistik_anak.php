<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusLingkarKepalaToDataStatistikAnak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_statistik_anak', function (Blueprint $table) {
            $table->string('status_lingkar_kepala')->nullable();
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
            $table->dropColumn('status_lingkar_kepala');
        });
    }
}
