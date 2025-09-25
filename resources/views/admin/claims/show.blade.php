<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Detalle de bono {{ $claim->code }}</h2></x-slot>
  <div class="py-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow space-y-4">
      <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div><dt class="text-xs text-slate-500">Beneficio</dt><dd class="font-medium">{{ \App\Models\Claim::BENEFITS[$claim->benefit] }}</dd></div>
        <div><dt class="text-xs text-slate-500">Fecha tentativa</dt><dd class="font-medium">{{ optional($claim->tentative_date)->format('Y-m-d') }}</dd></div>
        <div><dt class="text-xs text-slate-500">Nombre</dt><dd class="font-medium">{{ $claim->name }}</dd></div>
        <div><dt class="text-xs text-slate-500">Teléfono</dt><dd class="font-medium">{{ $claim->phone }}</dd></div>
        <div><dt class="text-xs text-slate-500">Email</dt><dd class="font-medium">{{ $claim->email }}</dd></div>
        <div><dt class="text-xs text-slate-500">Estado</dt><dd class="font-medium">{{ $claim->status }}</dd></div>
        <div class="sm:col-span-2"><dt class="text-xs text-slate-500">Creado</dt><dd class="font-medium">{{ $claim->created_at->format('Y-m-d H:i') }}</dd></div>
      </dl>

      <div>
        <h3 class="font-semibold mb-2">Referidos ({{ $claim->referrals->count() }})</h3>
        @forelse($claim->referrals as $r)
          <div class="border rounded-lg p-3 mb-2">
            <div><span class="text-slate-500 text-xs">Nombre:</span> {{ $r->name ?? '(sin nombre)' }}</div>
            <div><span class="text-slate-500 text-xs">Tel:</span> {{ $r->phone }}</div>
            <div><span class="text-slate-500 text-xs">Email:</span> {{ $r->email }}</div>
          </div>
        @empty
          <p class="text-slate-500 text-sm">Sin referidos.</p>
        @endforelse
      </div>

      <div>
        <h3 class="font-semibold mb-2">Bono</h3>
        <img class="max-w-full rounded-lg ring-1 ring-slate-200" src="{{ asset('storage/'.$claim->voucher_path) }}" alt="Bono">
        <div class="mt-3">
          <a class="inline-flex items-center justify-center rounded-xl px-4 py-2 bg-black text-white" href="{{ route('voucher.download', $claim->code) }}">Descargar PNG</a>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
