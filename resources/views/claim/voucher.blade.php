@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
  <div class="bg-white/80 backdrop-blur rounded-2xl shadow p-6 sm:p-8">
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 text-center">
      ¡Tu bono está listo!
    </h1>

    <p class="mt-3 text-center text-slate-700">
      Código:
      <span class="font-mono font-semibold">{{ $claim->code }}</span>
      —
      Beneficio:
      <span class="font-semibold">
        {{ \App\Models\Claim::BENEFITS[$claim->benefit] ?? $claim->benefit }}
      </span>
    </p>

    <div class="mt-6 rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
      <img
        src="{{ $imgUrl }}"
        alt="Bono digital"
        class="w-full h-auto block select-none"
        draggable="false"
      >
    </div>

    <div class="mt-8 flex items-center justify-center gap-3">
      {{-- ÚNICO botón visible para el cliente --}}
      <a
        href="{{ route('voucher.download', $claim->code) }}"
        class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-black text-white font-semibold shadow hover:opacity-90 transition"
      >
        Descargar imagen
      </a>
    </div>
  </div>
</div>
@endsection
