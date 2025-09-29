@extends('layouts.app') {{-- si prefieres <x-guest-layout>, igual funcionará el modal por el auto-loader --}}

@section('content')
<div class="max-w-2xl mx-auto py-12">
  <h1 class="text-2xl font-bold mb-6">
    Solicitar bono: <span class="text-slate-800">{{ $benefitLabel }}</span>
  </h1>

  {{-- Debug temporal: quítalo cuando veas que hay errores en sesión --}}
  @if ($errors->any())
    <pre class="text-xs bg-yellow-50 p-2 rounded border mb-4">{{ var_export($errors->toArray(), true) }}</pre>
  @endif

  <form action="{{ route('claim.store') }}" method="POST" class="space-y-5 bg-white p-6 rounded-2xl shadow">
    @csrf
    <input type="hidden" name="benefit" value="{{ $benefitKey }}">

    <div>
      <label class="block text-sm font-medium">Fecha tentativa</label>
      <input
        type="date"
        name="tentative_date"
        value="{{ old('tentative_date') }}"
        class="mt-1 block w-full rounded-lg border-slate-300"
        min="{{ now()->toDateString() }}"
        required
      >
      @error('tentative_date')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium">Nombre (opcional)</label>
        <input
          type="text"
          name="name"
          value="{{ old('name') }}"
          class="mt-1 block w-full rounded-lg border-slate-300"
          placeholder="Tu nombre"
        >
        @error('name')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Teléfono</label>
        <input
          type="text"
          name="phone"
          value="{{ old('phone') }}"
          class="mt-1 block w-full rounded-lg border-slate-300"
          placeholder="300 000 0000"
        >
        @error('phone')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium">Email</label>
        <input
          type="email"
          name="email"
          value="{{ old('email') }}"
          class="mt-1 block w-full rounded-lg border-slate-300 @error('email') border-red-500 @enderror"
          placeholder="tucorreo@mail.com"
          required
        >
        @error('email')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

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
{{-- Auto-carga SweetAlert si el layout no lo trajo, y luego muestra el error de email si existe --}}
@if ($errors->has('email'))
<script>
(function () {
  const msg = @json($errors->first('email'));

  function show() {
    if (window.Swal && typeof Swal.fire === 'function') {
      Swal.fire({ icon: 'info', title: 'Ya tenemos tu correo', text: msg, confirmButtonText: 'Entendido' });
    } else {
      alert(msg); // Fallback: garantiza feedback aunque no cargue SweetAlert
    }
  }

  // si no existe Swal, cargamos el CDN on-the-fly
  function ensureSwalThenShow() {
    if (window.Swal) return show();
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js';
    s.defer = true;
    s.onload = show;
    document.head.appendChild(s);

    // opcional: estilos
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
