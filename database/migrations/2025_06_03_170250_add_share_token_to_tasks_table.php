<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShareTokenToTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('share_token', 64)->nullable()->unique()->after('user_id');
            $table->timestamp('share_token_expires_at')->nullable()->after('share_token');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'share_token_expires_at']);
        });
    }
}
