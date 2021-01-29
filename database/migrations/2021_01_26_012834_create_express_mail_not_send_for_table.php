<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressMailNotSendForTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('express_mail_not_send_for', function (Blueprint $table) {
            $table->uuid('uid')->primary();
            $table->uuid('express_mail_uid')->index();
            $table->foreign('express_mail_uid')->references('uid')->on('express_mails');
            $table->uuid('group_uid')->nullable()->index();
            $table->foreign('group_uid')->references('uid')->on('groups');
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
        Schema::dropIfExists('express_mail_not_send_for');
    }
}
