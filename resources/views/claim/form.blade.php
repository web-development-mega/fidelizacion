@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12">
  <h1 class="text-2xl font-bold mb-6">
    Solicitar bono: <span class="text-slate-800">{{ $benefitLabel }}</span>
  </h1>

  {{-- Debug temporal de validaciones (puedes quitarlo luego) --}}
  @if ($errors->any())
    <pre class="text-xs bg-yellow-50 p-2 rounded border mb-4">{{ var_export($errors->toArray(), true) }}</pre>
  @endif

  <form action="{{ route('claim.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded-2xl shadow">
    @csrf
    <input type="hidden" name="benefit" value="{{ $benefitKey }}">

    {{-- Agenda tentativa --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium">Fecha tentativa de agendamiento <span class="text-red-600">*</span></label>
        <input
          type="date"
          name="fecha_tentativa"
          value="{{ old('fecha_tentativa') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('fecha_tentativa') border-red-500 @enderror"
          min="{{ now()->toDateString() }}"
          required
        >
        @error('fecha_tentativa')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Hora tentativa <span class="text-red-600">*</span></label>
        <input
          type="time"
          name="hora_tentativa"
          value="{{ old('hora_tentativa') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('hora_tentativa') border-red-500 @enderror"
          step="900"
          required
        >
        @error('hora_tentativa')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- Datos del cliente --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium">Nombre <span class="text-red-600">*</span></label>
        <input
          type="text"
          name="nombre"
          value="{{ old('nombre') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('nombre') border-red-500 @enderror"
          minlength="3" maxlength="100" required
          placeholder="Tu nombre completo"
        >
        @error('nombre')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Cédula <span class="text-red-600">*</span></label>
        <input
          type="text"
          name="cedula"
          value="{{ old('cedula') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('cedula') border-red-500 @enderror"
          inputmode="numeric" pattern="\d{6,12}" required
          placeholder="Solo números (6–12 dígitos)"
        >
        @error('cedula')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Teléfono <span class="text-red-600">*</span></label>
        <input
          type="text"
          name="telefono"
          value="{{ old('telefono') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('telefono') border-red-500 @enderror"
          inputmode="tel" pattern="\d{7,10}" required
          placeholder="3001234567"
        >
        @error('telefono')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Correo electrónico <span class="text-red-600">*</span></label>
        <input
          type="email"
          name="email"
          value="{{ old('email') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('email') border-red-500 @enderror"
          maxlength="150" required
          placeholder="tucorreo@mail.com"
        >
        @error('email')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="sm:col-span-2">
        <label class="block text-sm font-medium">Dirección <span class="text-red-600">*</span></label>
        <input
          type="text"
          name="direccion"
          value="{{ old('direccion') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('direccion') border-red-500 @enderror"
          maxlength="160" required
          placeholder="Ej: Calle 10 #20-30, Medellín"
        >
        @error('direccion')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- Vehículo --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium">Placa <span class="text-red-600">*</span></label>
        <input
          type="text"
          name="placa"
          value="{{ old('placa') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('placa') border-red-500 @enderror"
          pattern="[A-Za-z]{3}\d{3}" required
          placeholder="AAA123"
          oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,6)"
        >
        @error('placa')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div class="sm:col-span-2">
        <label class="block text-sm font-medium">Marca y modelo <span class="text-red-600">*</span></label>
        <input
          type="text"
          name="marca_modelo"
          value="{{ old('marca_modelo') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('marca_modelo') border-red-500 @enderror"
          maxlength="100" required
          placeholder="Ej: Chevrolet Onix 1.0"
        >
        @error('marca_modelo')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    {{-- Referidos (opcional) --}}
    <details class="rounded-lg border p-4">
      <summary class="cursor-pointer font-semibold">¿Quieres referir amigos? (hasta 3)</summary>
      <div id="referrals" class="mt-4 space-y-4">
        @for ($i = 0; $i < 3; $i++)
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input
              type="text"
              name="referrals[{{ $i }}][name]"
              value="{{ old("referrals.$i.name") }}"
              class="rounded-lg border-slate-300"
              placeholder="Nombre referido {{ $i + 1 }}"
            >
            <input
              type="text"
              name="referrals[{{ $i }}][phone]"
              value="{{ old("referrals.$i.phone") }}"
              class="rounded-lg border-slate-300"
              placeholder="Teléfono"
            >
            <input
              type="email"
              name="referrals[{{ $i }}][email]"
              value="{{ old("referrals.$i.email") }}"
              class="rounded-lg border-slate-300"
              placeholder="Email"
            >
          </div>
        @endfor
        <p class="text-xs text-slate-500">Déjalos vacíos si no deseas referir.</p>
      </div>
    </details>

    <button
      class="inline-flex items-center justify-center rounded-xl px-5 py-3 font-semibold bg-black text-white hover:opacity-90 active:scale-95 transition w-full">
      Generar bono
    </button>
  </form>
</div>

@push('scripts')
{{-- Si hubo error de unicidad en email/cedula/telefono/nombre, muéstralo con SweetAlert (fallback a alert). --}}
@if ($errors->any())
<script>
(function () {
  const fields = ['email','cedula','telefono','nombre'];
  let firstError = null;
  for (const f of fields) {
    if (@json($errors->has($f))) { firstError = @json($errors->first($f)); break; }
  }
  if (!firstError) return;

  function show(msg) {
    if (window.Swal && typeof Swal.fire === 'function') {
      Swal.fire({ icon: 'info', title: 'Atención', text: msg, confirmButtonText: 'Entendido' });
    } else {
      alert(msg);
    }
  }

  function ensureSwalThenShow() {
    if (window.Swal) return show(firstError);
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js';
    s.defer = true;
    s.onload = () => show(firstError);
    document.head.appendChild(s);

    const l = document.createElement('link');
    l.rel = 'stylesheet';
    l.href = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css';
    document.head.appendChild(l);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', ensureSwalThenShow);
  } else {
    setTimeout(ensureSwalThenShow, 0);
  }
})();
</script>
@endif
@endpush
@endsection
