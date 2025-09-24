<x-guest-layout>
  <div class="max-w-2xl mx-auto py-12">
    <h1 class="text-2xl font-bold mb-6">Solicitar bono: <span class="text-slate-800">{{ $benefitLabel }}</span></h1>

    <form action="{{ route('claim.store') }}" method="POST" class="space-y-5 bg-white p-6 rounded-2xl shadow">
      @csrf
      <input type="hidden" name="benefit" value="{{ $benefitKey }}">

      <div>
        <label class="block text-sm font-medium">Fecha tentativa</label>
        <input type="date" name="tentative_date" class="mt-1 block w-full rounded-lg border-slate-300"
               min="{{ now()->toDateString() }}" required>
        @error('tentative_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium">Nombre (opcional)</label>
          <input type="text" name="name" class="mt-1 block w-full rounded-lg border-slate-300" placeholder="Tu nombre">
        </div>
        <div>
          <label class="block text-sm font-medium">Teléfono</label>
          <input type="text" name="phone" class="mt-1 block w-full rounded-lg border-slate-300" placeholder="300 000 0000">
        </div>
        <div>
          <label class="block text-sm font-medium">Email</label>
          <input type="email" name="email" class="mt-1 block w-full rounded-lg border-slate-300" placeholder="tucorreo@mail.com">
        </div>
      </div>

      <details class="rounded-lg border p-4">
        <summary class="cursor-pointer font-semibold">¿Quieres referir amigos? (hasta 3)</summary>
        <div id="referrals" class="mt-4 space-y-4">
          @for($i=0;$i<3;$i++)
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <input type="text"  name="referrals[{{ $i }}][name]"  class="rounded-lg border-slate-300" placeholder="Nombre referido {{ $i+1 }}">
              <input type="text"  name="referrals[{{ $i }}][phone]" class="rounded-lg border-slate-300" placeholder="Teléfono">
              <input type="email" name="referrals[{{ $i }}][email]" class="rounded-lg border-slate-300" placeholder="Email">
            </div>
          @endfor
          <p class="text-xs text-slate-500">Déjalos vacíos si no deseas referir.</p>
        </div>
      </details>

      <button class="inline-flex items-center justify-center rounded-xl px-5 py-3 font-semibold bg-black text-white hover:opacity-90 active:scale-95 transition w-full">
        Generar bono
      </button>
    </form>
  </div>
</x-guest-layout>
