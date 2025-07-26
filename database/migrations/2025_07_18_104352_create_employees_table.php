<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Core Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('emp_id')->unique();

            // Company Details
            $table->string('reporting_manager')->nullable();
            $table->string('branches')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->date('date_of_join')->nullable();
            $table->date('date_of_probation')->nullable();


            // Contact Information
            $table->string('personal_phone_number');
            $table->string('office_phone_number')->nullable();
            $table->string('emergency_contact')->nullable();

            // Personal Details
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('religion')->nullable();
            
            // Address Information
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();

            // Identification & Bank Details
            $table->string('nid_number')->nullable()->unique();
            $table->string('passport_number')->nullable()->unique();
            $table->string('tin_number')->nullable()->unique();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_account_number')->nullable();

            // Attachments (storing file paths)
            $table->string('photo')->nullable();
            $table->string('nid_attachment')->nullable();
            $table->string('passport_attachment')->nullable();
            $table->string('tin_attachment')->nullable();
            $table->string('cv_pdf')->nullable();
            $table->string('certificate_attachment')->nullable();

            // Reference Information
            $table->string('reference_name')->nullable();
            $table->string('reference_phone_number')->nullable();
            
            // Education - Stored as JSON to handle multiple entries
            $table->json('education_details')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
