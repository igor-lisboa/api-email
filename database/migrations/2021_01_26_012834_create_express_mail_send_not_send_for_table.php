<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressMailSendNotSendForTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('express_mail_send_not_send_for', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('uid')->primary();
            $table->uuid('express_mail_send_uid')->index();
            $table->foreign('express_mail_send_uid')->references('uid')->on('express_mail_sends');
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
        Schema::dropIfExists('express_mail_send_not_send_for');
    }
}
