<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('uid')->primary();
            $table->uuid('user_uid')->index();
            $table->string('slug', 50)->unique();
            $table->integer('priority')->index()->default(1)->comment('Higher number => lower priority');
            $table->integer('quota_qtd')->index()->default(-1)->comment('Sends per month');
            $table->integer('quota_qtd_max')->index()->default(-1)->comment('Max sends per month');
            $table->smallInteger('quota_renovation')->index()->nullable()->comment('Quota renewal day');
            $table->boolean('active')->default(true)->index()->comment('0 - false | 1 - true');
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
        Schema::dropIfExists('mailers');
    }
}
