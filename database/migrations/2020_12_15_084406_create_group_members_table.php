<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->uuid('uid')->primary();
            $table->uuid('group_uid')->index();
            $table->uuid('email_uid')->index();
            $table->foreign('group_uid')->references('uid')->on('groups')->cascadeOnDelete();
            $table->foreign('email_uid')->references('uid')->on('emails')->cascadeOnDelete();
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
        Schema::dropIfExists('group_members');
    }
}
