<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sends', function (Blueprint $table) {
            $table->uuid('uid')->primary();
            $table->longText('content');
            $table->uuid('express_mail_uid')->index();
            $table->foreign('express_mail_uid')->references('uid')->on('express_mails');
            $table->boolean('sent')->index()->default(false);
            $table->longText('return')->nullable();
            $table->integer('attempts')->default(0);
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
        Schema::dropIfExists('sends');
    }
}
