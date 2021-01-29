<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressMailAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('express_mail_attachments', function (Blueprint $table) {
            $table->uuid('uid')->primary();
            $table->uuid('express_mail_uid')->index();
            $table->foreign('express_mail_uid')->references('uid')->on('express_mails');
            $table->string('file', 300);
            $table->string('name', 100)->nullable();
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
        Schema::dropIfExists('express_mail_attachments');
    }
}
