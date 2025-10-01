<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Asegurar columnas
        Schema::table('claims', function (Blueprint $table) {
            if (!Schema::hasColumn('claims', 'nombre'))          $table->string('nombre')->nullable();
            if (!Schema::hasColumn('claims', 'cedula'))          $table->string('cedula', 32)->nullable();
            if (!Schema::hasColumn('claims', 'telefono'))        $table->string('telefono', 32)->nullable();
            if (!Schema::hasColumn('claims', 'direccion'))       $table->string('direccion', 160)->nullable();
            if (!Schema::hasColumn('claims', 'email'))           $table->string('email')->nullable();
            if (!Schema::hasColumn('claims', 'placa'))           $table->string('placa', 16)->nullable();
            if (!Schema::hasColumn('claims', 'marca_modelo'))    $table->string('marca_modelo', 100)->nullable();
            if (!Schema::hasColumn('claims', 'fecha_tentativa')) $table->date('fecha_tentativa')->nullable();
            if (!Schema::hasColumn('claims', 'hora_tentativa'))  $table->time('hora_tentativa')->nullable();
        });

        // 2) Índices únicos (idempotentes)
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite soporta IF NOT EXISTS para índices
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS claims_nombre_unique   ON claims (nombre)');
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS claims_cedula_unique   ON claims (cedula)');
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS claims_telefono_unique ON claims (telefono)');
            DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS claims_email_unique    ON claims (email)');
        } else {
            // Otros motores: intentamos limpiar y volver a crear
            try { Schema::table('claims', fn(Blueprint $t) => $t->dropUnique('claims_nombre_unique')); } catch (\Throwable $e) {}
            try { Schema::table('claims', fn(Blueprint $t) => $t->dropUnique('claims_cedula_unique')); } catch (\Throwable $e) {}
            try { Schema::table('claims', fn(Blueprint $t) => $t->dropUnique('claims_telefono_unique')); } catch (\Throwable $e) {}
            try { Schema::table('claims', fn(Blueprint $t) => $t->dropUnique('claims_email_unique')); } catch (\Throwable $e) {}

            Schema::table('claims', function (Blueprint $t) {
                $t->unique('nombre',   'claims_nombre_unique');
                $t->unique('cedula',   'claims_cedula_unique');
                $t->unique('telefono', 'claims_telefono_unique');
                $t->unique('email',    'claims_email_unique');
            });
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('DROP INDEX IF EXISTS claims_nombre_unique');
            DB::statement('DROP INDEX IF EXISTS claims_cedula_unique');
            DB::statement('DROP INDEX IF EXISTS claims_telefono_unique');
            DB::statement('DROP INDEX IF EXISTS claims_email_unique');
        } else {
            Schema::table('claims', function (Blueprint $table) {
                try { $table->dropUnique('claims_nombre_unique'); } catch (\Throwable $e) {}
                try { $table->dropUnique('claims_cedula_unique'); } catch (\Throwable $e) {}
                try { $table->dropUnique('claims_telefono_unique'); } catch (\Throwable $e) {}
                try { $table->dropUnique('claims_email_unique'); } catch (\Throwable $e) {}
            });
        }

        // (Opcional) quitar columnas si quieres revertir del todo
        // Schema::table('claims', fn (Blueprint $t) => $t->dropColumn([...]));
    }
};
