<x-guest-layout>
  <div class="max-w-3xl mx-auto py-10 text-center">
    <h1 class="text-2xl font-bold mb-4">¡Tu bono está listo!</h1>
    <p class="text-slate-600 mb-6">Código: <strong>{{ $claim->code }}</strong> — Beneficio: <strong>{{ \App\Models\Claim::BENEFITS[$claim->benefit] }}</strong></p>

    <img src="{{ $imgUrl }}" alt="Bono digital" class="mx-auto rounded-2xl ring-1 ring-slate-200 shadow mb-6 max-w-full">

    <div class="flex items-center justify-center gap-3">
      <a href="{{ route('voucher.download', $claim->code) }}" class="inline-flex items-center justify-center rounded-xl px-5 py-3 font-semibold bg-black text-white hover:opacity-90 active:scale-95 transition">
        Descargar imagen
      </a>
      <a href="{{ route('landing') }}" class="inline-flex items-center justify-center rounded-xl px-5 py-3 font-semibold bg-slate-700 text-white hover:opacity-90 active:scale-95 transition">
        Crear otro bono
      </a>
    </div>
  </div>
</x-guest-layout>
