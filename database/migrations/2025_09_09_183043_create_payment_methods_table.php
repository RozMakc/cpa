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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('payment_system_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_bik')->nullable();
            $table->string('bank_rs')->nullable();
            $table->timestamps();

            
            $table->foreign('payment_system_id')
            ->references('id')
            ->on('payment_systems')
            ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
