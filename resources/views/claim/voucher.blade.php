@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
  <div class="bg-white/80 backdrop-blur rounded-2xl shadow p-6 sm:p-8 print:shadow-none print:bg-white">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 text-center">
      ¡Tu bono está listo!
    </h1>

    <p class="mt-3 text-center text-slate-700">
      Código:
      <span id="voucher-code" class="font-mono font-semibold">{{ $claim->code }}</span>
      —
      Beneficio:
      <span class="font-semibold">
        {{ \App\Models\Claim::BENEFITS[$claim->benefit] ?? $claim->benefit }}
      </span>
    </p>

    {{-- Datos del cliente / cita --}}
    <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
      <div>
        <dt class="text-slate-500">Nombre</dt>
        <dd class="font-medium">{{ $claim->nombre ?: '—' }}</dd>
      </div>
      <div>
        <dt class="text-slate-500">Cédula</dt>
        <dd class="font-medium">{{ $claim->cedula ?: '—' }}</dd>
      </div>
      <div>
        <dt class="text-slate-500">Teléfono</dt>
        <dd class="font-medium">{{ $claim->telefono ?: '—' }}</dd>
      </div>
      <div>
        <dt class="text-slate-500">Correo</dt>
        <dd class="font-medium">{{ $claim->email ?: '—' }}</dd>
      </div>
      <div>
        <dt class="text-slate-500">Placa</dt>
        <dd class="font-medium">{{ $claim->placa ?: '—' }}</dd>
      </div>
      <div>
        <dt class="text-slate-500">Marca y modelo</dt>
        <dd class="font-medium">{{ $claim->marca_modelo ?: '—' }}</dd>
      </div>
      <div class="sm:col-span-2">
        <dt class="text-slate-500">Fecha y hora tentativa</dt>
        <dd class="font-medium">
          {{ optional($claim->fecha_tentativa)->format('Y-m-d') ?: '—' }}
          @if ($claim->hora_tentativa)
            {{ $claim->hora_tentativa }}
          @endif
        </dd>
      </div>
    </dl>

    <div class="mt-6 rounded-2xl overflow-hidden border border-slate-200 shadow-sm print:border-0 print:shadow-none">
      <img
        src="{{ $imgUrl }}"
        alt="Bono digital {{ $claim->code }}"
        class="w-full h-auto block select-none"
        draggable="false"
      >
    </div>

    {{-- Botones visibles: solo Descargar e Imprimir --}}
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3 print:hidden">
      <a
        href="{{ route('voucher.download', $claim->code) }}"
        class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-black text-white font-semibold shadow hover:opacity-90 transition"
      >
        Descargar imagen
      </a>

      <button
        type="button"
        onclick="window.print()"
        class="inline-flex items-center justify-center px-5 py-3 rounded-xl border font-semibold hover:bg-slate-50 transition"
      >
        Imprimir
      </button>
    </div>
  </div>
</div>
@endsection
