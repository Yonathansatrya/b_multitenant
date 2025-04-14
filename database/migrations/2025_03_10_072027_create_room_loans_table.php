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
        Schema::create('room_loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code')->unique();
            $table->string('loan_name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('loan_status', ['Pending','Reject','Approve','Finish'])->default('Pending');
            $table->timestamps();
        });

        Schema::create('room_loan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_loan_id')->constrained('room_loans')->onDelete('cascade');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_loans');
        Schema::dropIfExists('room_loan_details');
    }
};
