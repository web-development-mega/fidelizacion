<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Claim;

class UpdateClaimRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    /**
     * Normaliza entradas (igual que en Store):
     * - email: trim + lowercase
     * - cedula/telefono: solo dígitos
     * - placa: uppercase sin separadores (AAA123)
     * - textos: trim colapsando espacios
     */
    protected function prepareForValidation(): void
    {
        $digits = fn ($v) => $v !== null ? preg_replace('/\D+/', '', (string)$v) : null;
        $trim   = fn ($v) => $v !== null ? trim(preg_replace('/\s+/', ' ', (string)$v)) : null;

        $placa = $this->input('placa');
        if ($placa !== null) {
            $placa = strtoupper(str_replace([' ', '-'], '', (string)$placa));
        }

        $this->merge([
            'nombre'          => $trim($this->input('nombre')),
            'cedula'          => $digits($this->input('cedula')),
            'telefono'        => $digits($this->input('telefono')),
            'direccion'       => $trim($this->input('direccion')),
            'email'           => $this->input('email') ? strtolower($trim($this->input('email'))) : null,
            'placa'           => $placa,
            'marca_modelo'    => $trim($this->input('marca_modelo')),
        ]);
    }

    public function rules(): array
    {
        // Si usas route model binding: routes.../{claim}, esto trae el modelo:
        $claimModel = $this->route('claim');             // \App\Models\Claim|NULL
        // Si no, intenta con 'id':
        $id = $claimModel->id ?? $this->route('id');

        return [
            'benefit'         => ['required', Rule::in(array_keys(Claim::BENEFITS))],

            'nombre'          => ['required','string','min:3','max:100', Rule::unique('claims','nombre')->ignore($id)],
            'cedula'          => ['required','regex:/^\d{6,12}$/',       Rule::unique('claims','cedula')->ignore($id)],
            'telefono'        => ['required','regex:/^\d{7,10}$/',       Rule::unique('claims','telefono')->ignore($id)],
            'direccion'       => ['required','string','max:160'],
            'email'           => ['required','email:rfc','max:150',      Rule::unique('claims','email')->ignore($id)],

            'placa'           => ['required','regex:/^[A-Z]{3}\d{3}$/','max:8'],
            'marca_modelo'    => ['required','string','max:100'],

            'fecha_tentativa' => ['required','date','after_or_equal:today'],
            'hora_tentativa'  => ['required','date_format:H:i'],
        ];
    }

    public function attributes(): array
    {
        return [
            'benefit'         => 'beneficio',
            'nombre'          => 'Nombre',
            'cedula'          => 'Cédula',
            'telefono'        => 'Teléfono',
            'direccion'       => 'Dirección',
            'email'           => 'Correo electrónico',
            'placa'           => 'Placa',
            'marca_modelo'    => 'Marca y modelo',
            'fecha_tentativa' => 'Fecha tentativa de agendamiento',
            'hora_tentativa'  => 'Hora tentativa de agendamiento',
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
}
