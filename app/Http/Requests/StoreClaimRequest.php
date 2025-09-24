<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'benefit'        => ['required','in:mega_combo,revision_bateria,cambio_aceite,trabajos_autorizados'],
            'tentative_date' => ['required','date','after_or_equal:today'],

            'name'  => ['nullable','string','max:120'],
            'phone' => ['nullable','string','max:30'],
            'email' => ['nullable','email','max:150'],

            'referrals'         => ['nullable','array','max:3'],
            'referrals.*.name'  => ['nullable','string','max:120'],
            'referrals.*.phone' => ['nullable','string','max:30'],
            'referrals.*.email' => ['nullable','email','max:150'],
        ];
    }
}
