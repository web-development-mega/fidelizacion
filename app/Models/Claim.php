<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Claim extends Model
{
    /**
     * Campos permitidos para asignación masiva.
     * Incluimos los nuevos y los legacy (mientras conviven).
     */
    protected $fillable = [
        'benefit', 'code', 'qr_path', 'voucher_path', 'status', 'meta',

        // === Nuevos campos (formulario actual) ===
        'nombre', 'cedula', 'telefono', 'direccion', 'email',
        'placa', 'marca_modelo', 'fecha_tentativa', 'hora_tentativa',

        // === Legacy (compat) ===
        'tentative_date', 'name', 'phone',
    ];

    protected $casts = [
        // Nuevo
        'fecha_tentativa' => 'date',
        // Legacy (por si algo viejo aún lo usa)
        'tentative_date'  => 'date',
        'meta'            => 'array',
    ];

    public const BENEFITS = [
        'cambio_aceite'            => '10% cambio de aceite',
        'trabajos_autorizados'     => '10% trabajos autorizados',
        'mega_combo'               => '70% en alineación y balanceo',
        // alias por compatibilidad si aún tienes registros antiguos:
        'mantenimiento_preventivo' => '70% en alineación y balanceo',
        'revision_bateria'         => 'Revisión GRATIS de batería',
    ];

    public function referrals(): HasMany
    {
        return $this->hasMany(ClaimReferral::class);
    }

    /* =======================================================
       Accessors/Mutators de compatibilidad (legacy ↔ nuevos)
       ======================================================= */

    // name <-> nombre
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => $value ?? ($attr['nombre'] ?? null),
            set: fn ($value) => ['name' => $value, 'nombre' => $value]
        );
    }

    // phone <-> telefono
    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => $value ?? ($attr['telefono'] ?? null),
            set: fn ($value) => ['phone' => $value, 'telefono' => $value]
        );
    }

    // tentative_date <-> fecha_tentativa
    protected function tentativeDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attr) => $value ?? ($attr['fecha_tentativa'] ?? null),
            set: fn ($value) => ['tentative_date' => $value, 'fecha_tentativa' => $value]
        );
    }
}
