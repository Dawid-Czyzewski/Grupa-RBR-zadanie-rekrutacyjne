<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->string('name', 255);
            $table->text('description')->nullable();

            $table->foreignId('priority_id')
                  ->constrained('priorities')
                  ->onDelete('restrict');

            $table->foreignId('status_id')
                  ->constrained('statuses')
                  ->onDelete('restrict');

            $table->date('due_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
