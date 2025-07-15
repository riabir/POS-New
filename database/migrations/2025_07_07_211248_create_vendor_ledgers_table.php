<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_id')->nullable()->constrained()->onDelete('set null');
            $table->date('transaction_date');
            $table->string('description');
            $table->string('bill_by')->nullable(); // Who created the purchase
            $table->string('received_by')->nullable(); // Who processed the payment
            $table->string('payment_type')->nullable(); // e.g., Cash, Bank
            $table->decimal('debit', 15, 2)->default(0);  // Bill amount we owe
            $table->decimal('credit', 15, 2)->default(0); // Payment amount we made
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_ledgers');
    }
};
