<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shareholder_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shareholder_id')->constrained()->onDelete('cascade');
            $table->date('transaction_date');
            $table->enum('type', ['Investment', 'Dividend', 'Withdrawal']);
            $table->decimal('amount', 15, 2); // Supports large amounts
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shareholder_transactions');
    }
};