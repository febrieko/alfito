@if(!empty($company->logo_url))
    <img src="{{ $company->logo_url }}" alt="{{ $company->short_name ?? 'ALFITO' }}" {{ $attributes->merge(['class' => 'h-10 w-auto object-contain']) }}>
@else
    <div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-400 to-blue-600 flex items-center justify-center text-navy-950 font-black text-xl shadow-md shadow-sky-500/25">
            🧭
        </div>
        <span class="text-xl font-black tracking-tight text-slate-900">
            {{ $company->short_name ?? 'ALFITO' }}
        </span>
    </div>
@endif
