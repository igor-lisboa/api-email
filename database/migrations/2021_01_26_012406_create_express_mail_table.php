<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpressMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('express_mails', function (Blueprint $table) {
            $table->uuid('uid')->primary();
            $table->uuid('user_uid')->index();
            $table->string('subject', 150)->nullable();
            $table->unsignedBigInteger('send_type_id')->index();
            $table->foreign('send_type_id')->references('id')->on('send_types');
            $table->unsignedBigInteger('mailer_id')->index();
            $table->foreign('mailer_id')->references('id')->on('mailers');
            $table->uuid('from_uid')->index();
            $table->foreign('from_uid')->references('uid')->on('emails');
            $table->uuid('answer_to_uid')->index()->nullable();
            $table->foreign('answer_to_uid')->references('uid')->on('emails');
            $table->uuid('template_uid')->index()->nullable();
            $table->foreign('template_uid')->references('uid')->on('templates');
            $table->boolean('show_online')->default(false);
            $table->boolean('embed_image')->default(false);
            $table->boolean('markdown')->default(false);
            $table->dateTimeTz('send_moment');
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
        Schema::dropIfExists('express_mails');
    }
}
