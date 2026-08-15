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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();

            $table->string('requester_number', 20)->unique();
            $table->text('description');
            $table->json('form_data');

            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'closed'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->text('admin_notes')->nullable();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('major_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // تتبع التدرج والسير الإداري
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete(); // المحال إليه الطلب حالياً
            $table->foreignId('action_by')->nullable()->constrained('users')->nullOnDelete();   // مقتضي الإجراء النهائي

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
