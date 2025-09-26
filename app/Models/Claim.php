<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Claim extends Model
{
    protected $fillable = [
        'benefit','tentative_date','name','phone','email','code','qr_path','voucher_path','status','meta'
    ];

    protected $casts = [
        'tentative_date' => 'date',
        'meta' => 'array',
    ];

    public const BENEFITS = [
        'cambio_aceite'         => '10% cambio de aceite',
        'trabajos_autorizados'  => '10% trabajos autorizados',
        'mega_combo'            => '70% en alineación y balanceo',
        // alias por compatibilidad si aún tienes registros antiguos:
        'mantenimiento_preventivo' => '70% en alineación y balanceo',
        'revision_bateria'      => 'Revisión GRATIS de batería',
    ];

    public function referrals(): HasMany
    {
        return $this->hasMany(ClaimReferral::class);
    }
}
