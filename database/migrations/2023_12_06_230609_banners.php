<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Banners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id_banner');
            $table->string('name');
            $table->string('subtitle');
            $table->string('image');
            $table->dateTime('created_at')->nullable('false');
            $table->dateTime('updated_at')->nullable('false');
            $table->dateTime('deleted_at')->nullable('true');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
