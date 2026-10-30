@props(['mobile' => false])

@if($mobile)
    <div class="flex items-center justify-between py-2 border-b border-slate-800 text-xs text-slate-300 notranslate"
         x-data="{ currentLang: (localStorage.getItem('site_language') || (document.cookie.includes('googtrans=/id/en') ? 'en' : 'id')) }">
        <span class="flex items-center gap-1.5 font-medium">
            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
            </svg>
            <span>Bahasa / Language</span>
        </span>
        <div class="flex items-center bg-navy-900 border border-slate-700/70 rounded-xl p-0.5 text-xs font-semibold shadow-inner">
            <button type="button"
                    @click="window.switchSiteLanguage('id')"
                    :class="currentLang === 'id' ? 'bg-sky-500 text-navy-950 font-bold shadow' : 'text-slate-400 hover:text-white'"
                    class="px-3 py-1 rounded-lg transition-all flex items-center gap-1">
                <span>🇮🇩</span>
                <span>ID</span>
            </button>
            <button type="button"
                    @click="window.switchSiteLanguage('en')"
                    :class="currentLang === 'en' ? 'bg-sky-500 text-navy-950 font-bold shadow' : 'text-slate-400 hover:text-white'"
                    class="px-3 py-1 rounded-lg transition-all flex items-center gap-1">
                <span>🇬🇧</span>
                <span>EN</span>
            </button>
        </div>
    </div>
@else
    <div class="notranslate inline-flex items-center bg-navy-800/80 border border-slate-700/60 rounded-xl p-0.5 text-xs font-semibold shadow-inner"
         x-data="{ currentLang: (localStorage.getItem('site_language') || (document.cookie.includes('googtrans=/id/en') ? 'en' : 'id')) }">
        <button type="button"
                @click="window.switchSiteLanguage('id')"
                :class="currentLang === 'id' ? 'bg-sky-500 text-navy-950 font-bold shadow' : 'text-slate-400 hover:text-slate-200'"
                class="px-2.5 py-1 rounded-lg transition-all flex items-center gap-1.5"
                title="Bahasa Indonesia">
            <span>🇮🇩</span>
            <span>ID</span>
        </button>
        <button type="button"
                @click="window.switchSiteLanguage('en')"
                :class="currentLang === 'en' ? 'bg-sky-500 text-navy-950 font-bold shadow' : 'text-slate-400 hover:text-slate-200'"
                class="px-2.5 py-1 rounded-lg transition-all flex items-center gap-1.5"
                title="English">
            <span>🇬🇧</span>
            <span>EN</span>
        </button>
    </div>
@endif

