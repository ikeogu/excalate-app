<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proximity_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('duration_type')->nullable();
            $table->string('status')->nullable();
            $table->integer('min_distance')->nullable();
            $table->integer('max_distance')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('min_price')->nullable();
            $table->integer('max_price')->nullable();

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
        Schema::dropIfExists('proximity_plans');
    }
};