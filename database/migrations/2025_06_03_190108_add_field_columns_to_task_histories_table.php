<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('task_histories', function (Blueprint $table) {
            $table->string('field')->after('task_id');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('task_histories', function (Blueprint $table) {
            $table->dropColumn(['field', 'old_value', 'new_value']);
        });
    }
};
