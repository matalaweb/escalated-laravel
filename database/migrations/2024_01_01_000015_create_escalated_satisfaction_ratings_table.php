<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escalated_satisfaction_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->unique()->constrained('escalated_tickets')->cascadeOnDelete();
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->nullableMorphs('rated_by');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escalated_satisfaction_ratings');
    }
};
