<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_variables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('uid')->primary();
            $table->uuid('template_uid')->index();
            $table->foreign('template_uid')->references('uid')->on('templates')->cascadeOnDelete();
            $table->string('slug', 30);
            $table->string('description', 100)->nullable();
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
        Schema::dropIfExists('template_variables');
    }
}
