<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimReferral extends Model
{
    protected $fillable = ['claim_id','name','phone','email','position'];

    public function claim(): BelongsTo
    {
        return $this->belongsTo(Claim::class);
    }
}
