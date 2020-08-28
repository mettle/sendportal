<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('api_token', 80)->after('password')->unique()->nullable()->default(null);
            $table->unsignedInteger('current_workspace_id')->nullable()->default(null)->after('api_token');
            $table->string('locale', 100)->after('remember_token')->default('en');
        });
    }
}
