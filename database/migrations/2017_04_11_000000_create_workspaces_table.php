<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkspacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id')->index();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('workspace_users', function (Blueprint $table) {
            $table->unsignedInteger('workspace_id');
            $table->unsignedInteger('user_id')->index();
            $table->string('role', 20);
            $table->timestamps();

            $table->foreign('workspace_id')->references('id')->on('workspaces');
            $table->unique(['workspace_id', 'user_id']);
        });
    }
}
