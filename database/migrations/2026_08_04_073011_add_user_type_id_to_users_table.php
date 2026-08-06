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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_name')->unique()->after('password');
            $table->string('city')->after('user_name');
            $table->string('phone_number')->after('city');
            $table->enum('gender', ['male', 'female'])->after('phone_number');
            $table->boolean('is_active')->default(true)->after('gender');
            $table->string('profile_image')->nullable()->after('is_active');
            $table->foreignId('user_type_id')->constrained('user_types')->cascadeOnDelete()->after('profile_image');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropColumn(['user_name', 'city', 'phone_number', 'gender', 'is_active', 'profile_image', 'user_type_id']);
        });
    }
};
