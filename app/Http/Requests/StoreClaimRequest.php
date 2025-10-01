<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreClaimRequest extends FormRequest
{
    public function authorize(): bool { return true; }

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
            'benefit'          => ['required','in:mega_combo,revision_bateria,cambio_aceite,trabajos_autorizados'],
            'fecha_tentativa'  => ['required','date','after_or_equal:today'],
            'hora_tentativa'   => ['required','date_format:H:i'],

            // Unicidades globales
            'email'            => ['required','email','max:150', Rule::unique('claims','email')],
            'nombre'           => ['required','string','max:100', Rule::unique('claims','nombre')],
            'cedula'           => ['required','regex:/^\d{6,12}$/', Rule::unique('claims','cedula')],
            'telefono'         => ['required','regex:/^\d{7,10}$/', Rule::unique('claims','telefono')],

            'direccion'        => ['required','string','max:160'],
            'placa'            => ['required','regex:/^[A-Z]{3}\d{3}$/','max:8'],
            'marca_modelo'     => ['required','string','max:100'],

            'referrals'         => ['nullable','array','max:3'],
            'referrals.*.name'  => ['nullable','string','max:120'],
            'referrals.*.phone' => ['nullable','string','max:30'],
            'referrals.*.email' => ['nullable','email','max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'    => 'Este correo ya tiene un bono registrado.',
            'cedula.unique'   => 'Esta cédula ya tiene un bono registrado.',
            'telefono.unique' => 'Este teléfono ya tiene un bono registrado.',
            'nombre.unique'   => 'Este nombre ya tiene un bono registrado.',

            'placa.regex'     => 'La placa debe ser como AAA123 (sin guion).',
            'cedula.regex'    => 'La cédula debe contener entre 6 y 12 dígitos.',
            'telefono.regex'  => 'El teléfono debe contener entre 7 y 10 dígitos.',
        ];
    }

    /** Reglas de negocio para hora según día de la semana. */
    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            // si ya falló lo básico, no seguimos
            if ($v->errors()->any()) return;

            $date = $this->date('fecha_tentativa');     // Carbon|null
            $time = (string) $this->input('hora_tentativa');

            if (!$date || !$time) return;

            $dow = $date->dayOfWeek; // 0=Dom, 6=Sab
            $weekend = ($dow === 0 || $dow === 6);

            $allowed = $this->generateSlots('06:30', $weekend ? '10:30' : '14:00', 30);
            if ($weekend) {
                // excepción explícita solicitada
                if (!in_array('10:40', $allowed, true)) {
                    $allowed[] = '10:40';
                }
            }

            if (!in_array($time, $allowed, true)) {
                $msg = $weekend
                    ? 'La hora debe estar entre 6:30 y 10:40 (fines de semana) en intervalos de 30 minutos.'
                    : 'La hora debe estar entre 6:30 y 14:00 en intervalos de 30 minutos.';
                $v->errors()->add('hora_tentativa', $msg);
            }
        });
    }

    /** Devuelve array de strings "HH:MM" cada $stepMin minutos entre $start y $end (incluidos). */
    private function generateSlots(string $start, string $end, int $stepMin): array
    {
        [$sH,$sM] = array_map('intval', explode(':', $start));
        [$eH,$eM] = array_map('intval', explode(':', $end));
        $cur = Carbon::createFromTime($sH,$sM,0);
        $last= Carbon::createFromTime($eH,$eM,0);

        $slots = [];
        while ($cur->lte($last)) {
            $slots[] = $cur->format('H:i');
            $cur->addMinutes($stepMin);
        }
        return $slots;
    }
}
