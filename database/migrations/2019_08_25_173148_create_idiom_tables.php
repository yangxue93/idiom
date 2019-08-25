<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIdiomTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('idiom.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('idiom.database.rd_cyjl_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('idiom', 190)->unique();
            $table->string('istype', 60);
            $table->string('datetimes',250);
            $table->string('error_tab',250);
            $table->string('content')->nullable();
            $table->smallIncrements('hot', 5)->nullable();
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
        Schema::dropIfExists(config('idiom.database.rd_cyjl_table'));
    }
}
