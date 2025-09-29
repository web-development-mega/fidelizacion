<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('benefit'); // mega_combo, revision_bateria, cambio_aceite, trabajos_autorizados
            $table->date('tentative_date');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('code')->unique();     // código del bono
            $table->string('qr_path');            // storage/app/public/...
            $table->string('voucher_path');       // storage/app/public/...
            $table->enum('status', ['issued','redeemed','cancelled'])->default('issued');
            $table->json('meta')->nullable();     // ip, ua, etc.
            $table->timestamps();
        });
    }
};
