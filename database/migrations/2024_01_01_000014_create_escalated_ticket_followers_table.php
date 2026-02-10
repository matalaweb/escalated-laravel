<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escalated_ticket_followers', function (Blueprint $table) {
            $table->foreignId('ticket_id')->constrained('escalated_tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['ticket_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escalated_ticket_followers');
    }
};
