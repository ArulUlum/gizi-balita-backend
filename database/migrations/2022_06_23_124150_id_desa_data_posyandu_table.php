<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IdDesaDataPosyanduTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_posyandu', function (Blueprint $table) {
            $table->foreignId('id_desa')->nullable()->constrained('data_desa', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_posyandu', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_desa');
        });
    }
}
