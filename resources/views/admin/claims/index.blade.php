@extends('layouts.app')

@section('content')
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      {{-- Header --}}
      <div class="mb-4">
        <h2 class="font-semibold text-xl text-gray-800">Registros de bonos</h2>
      </div>

      <div class="bg-white p-6 rounded-xl shadow space-y-4">
        <form class="flex flex-wrap gap-3 items-end">
          <div>
            <label class="block text-sm text-slate-600">Beneficio</label>
            <select name="benefit" class="rounded-lg border-slate-300">
              <option value="">Todos</option>
              @foreach(\App\Models\Claim::BENEFITS as $k=>$v)
                <option value="{{ $k }}" @selected(request('benefit')===$k)>{{ $v }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block text-sm text-slate-600">Estado</label>
            <select name="status" class="rounded-lg border-slate-300">
              <option value="">Todos</option>
              @foreach(['issued'=>'Emitido','redeemed'=>'Redimido','cancelled'=>'Cancelado'] as $k=>$v)
                <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="rounded-xl bg-black text-white px-4 py-2">
            Filtrar
          </button>

          {{-- Botón Exportar (conserva filtros actuales) --}}
          @php($qs = request()->only('benefit','status'))
          <a
            href="{{ route('admin.claims.export', $qs) }}"
            class="rounded-xl bg-emerald-600 text-white px-4 py-2 inline-flex items-center gap-2"
            onclick="this.classList.add('opacity-70','pointer-events-none'); this.textContent='Exportando…';"
          >
            Exportar Excel
          </a>
        </form>

        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left bg-slate-50">
                <th class="p-2">ID</th>
                <th class="p-2">Código</th>
                <th class="p-2">Beneficio</th>
                <th class="p-2">Fecha</th>
                <th class="p-2">Cliente</th>
                <th class="p-2">Teléfono</th>
                <th class="p-2">Email</th>
                <th class="p-2">#Ref.</th>
                <th class="p-2">Estado</th>
                <th class="p-2">Creado</th>
                <th class="p-2">Bono</th>
              </tr>
            </thead>
            <tbody>
              @foreach($claims as $c)
                <tr class="border-b align-top">
                  <td class="p-2">{{ $c->id }}</td>
                  <td class="p-2 font-mono">{{ $c->code }}</td>
                  <td class="p-2">{{ \App\Models\Claim::BENEFITS[$c->benefit] ?? $c->benefit }}</td>
                  <td class="p-2">{{ optional($c->tentative_date)->format('Y-m-d') }}</td>
                  <td class="p-2">{{ $c->name }}</td>
                  <td class="p-2">{{ $c->phone }}</td>
                  <td class="p-2">{{ $c->email }}</td>
                  <td class="p-2">{{ $c->referrals_count ?? ($c->referrals->count() ?? 0) }}</td>
                  <td class="p-2">{{ $c->status }}</td>
                  <td class="p-2">{{ optional($c->created_at)->format('Y-m-d H:i') }}</td>
                  <td class="p-2">
                    <a class="text-blue-600 underline" href="{{ route('voucher.show',$c->code) }}" target="_blank">ver</a>
                  </td>
                </tr>
                @if(($c->referrals_count ?? 0) || ($c->relationLoaded('referrals') && $c->referrals->count()))
                  <tr class="border-b bg-slate-50/50">
                    <td class="p-2 text-slate-500 text-xs" colspan="11">
                      @foreach($c->referrals as $r)
                        <div>• {{ $r->name ?? '(sin nombre)' }} — {{ $r->phone }} — {{ $r->email }}</div>
                      @endforeach
                    </td>
                  </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>

        {{ $claims->links() }}
      </div>
    </div>
  </div>
@endsection
