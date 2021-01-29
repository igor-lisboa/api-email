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
            $table->foreignUuid('send_uid')->index()->constrained(null, 'uid');
            $table->foreignUuid('email_uid')->nullable()->index()->constrained(null, 'uid');
            $table->foreignUuid('group_uid')->nullable()->index()->constrained(null, 'uid');
            $table->foreignId('destiny_type_id')->constrained();
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
