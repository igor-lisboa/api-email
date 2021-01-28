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
            $table->uuid('uid')->primary();
            $table->uuid('send_uid')->index();
            $table->uuid('email_uid')->nullable()->index();
            $table->uuid('group_uid')->nullable()->index();
            $table->unsignedBigInteger('destiny_type_id');
            $table->foreign('send_uid')->references('uid')->on('sends');
            $table->foreign('email_uid')->references('uid')->on('emails');
            $table->foreign('group_uid')->references('uid')->on('groups');
            $table->foreign('destiny_type_id')->references('id')->on('destiny_types');
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
