<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedInteger('workspace_id')->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('role')->nullable();
            $table->string('email');
            $table->string('token', 40)->unique();
            $table->timestamps();

            $table->foreign('workspace_id')->references('id')->on('workspaces');
        });
    }
}
