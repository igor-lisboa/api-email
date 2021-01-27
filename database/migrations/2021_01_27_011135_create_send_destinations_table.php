<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendDestinationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_destinations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('uid')->primary();
            $table->uuid('send_uid')->index();
            $table->uuid('email_uid')->nullable()->index();
            $table->uuid('group_uid')->nullable()->index();
            $table->uuid('destiny_type_uid');
            $table->foreign('send_uid')->references('uid')->on('sends');
            $table->foreign('email_uid')->references('uid')->on('emails');
            $table->foreign('group_uid')->references('uid')->on('groups');
            $table->foreign('destiny_type_uid')->references('uid')->on('destiny_types');
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
        Schema::dropIfExists('send_destinations');
    }
}
