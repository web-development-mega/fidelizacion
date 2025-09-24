<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('claim_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('claim_id')->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedTinyInteger('position')->default(1); // 1..3
            $table->timestamps();
        });
    }
};
