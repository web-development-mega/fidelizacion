<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    // Normaliza el email para evitar falsos duplicados (espacios/mayúsculas)
    protected function prepareForValidation(): void
    {
        $email = $this->input('email');
        if ($email !== null) {
            $this->merge(['email' => strtolower(trim((string) $email))]);
        }
    }

    public function rules(): array
    {
        return [
            'benefit'        => ['required','in:mega_combo,revision_bateria,cambio_aceite,trabajos_autorizados'],
            'tentative_date' => ['required','date','after_or_equal:today'],

            // Un correo = un bono (global)
            'email' => ['nullable','email','max:150', Rule::unique('claims','email')],

            // Si prefieres "un correo por beneficio", usa esto en vez de la línea anterior:
            // 'email' => ['nullable','email','max:150',
            //     Rule::unique('claims','email')->where(fn($q) => $q->where('benefit', $this->input('benefit')))
            // ],

            'name'  => ['nullable','string','max:120'],
            'phone' => ['nullable','string','max:30'],

            'referrals'         => ['nullable','array','max:3'],
            'referrals.*.name'  => ['nullable','string','max:120'],
            'referrals.*.phone' => ['nullable','string','max:30'],
            'referrals.*.email' => ['nullable','email','max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este correo ya tiene un bono registrado. Si no encuentras tu bono, contáctanos.',
            'email.email'  => 'Escribe un correo válido (ej. nombre@dominio.com).',
            'email.max'    => 'El correo no puede superar 150 caracteres.',
        ];
    }
}

