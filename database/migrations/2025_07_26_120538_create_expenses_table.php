<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('expense_type_id')->constrained()->onDelete('restrict');
            
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('days');
            $table->decimal('amount', 10, 2);
            $table->decimal('total', 10, 2);

            $table->text('particulars')->nullable();
            $table->string('voucher')->nullable(); // Stores the file path

            // Approval Workflow Fields
            $table->enum('status', ['pending', 'verified', 'approved', 'rejected', 'paid'])->default('pending');
            
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->text('verifier_remarks')->nullable();

            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('approver_remarks')->nullable();

            $table->foreignId('paid_by')->nullable()->constrained('users');
            $table->timestamp('paid_at')->nullable();
            $table->text('payment_remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};