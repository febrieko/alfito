<section>
    <header class="mb-6 pb-4 border-b border-slate-800 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <span>Perbarui Kata Sandi</span>
                <span class="text-sky-400">🔒</span>
            </h2>
            <p class="mt-1 text-xs text-slate-400">
                Gunakan kombinasi sandi yang kuat untuk menjaga keamanan akun Anda.
            </p>
        </div>
        <span class="text-[11px] px-2.5 py-1 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20 font-medium">
            Keamanan
        </span>
    </header>

    @if (session('status') === 'password-updated')
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>Kata sandi Anda berhasil diperbarui!</span>
        </div>
    @endif

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Kata Sandi Saat Ini <span class="text-rose-400">*</span>
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                   autocomplete="current-password" placeholder="••••••••" />
            @if(isset($errors) && $errors->updatePassword->has('current_password'))
                <p class="text-rose-400 text-xs mt-1.5">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Kata Sandi Baru <span class="text-rose-400">*</span>
            </label>
            <input id="update_password_password" name="password" type="password"
                   class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                   autocomplete="new-password" placeholder="Minimal 8 karakter..." />
            @if(isset($errors) && $errors->updatePassword->has('password'))
                <p class="text-rose-400 text-xs mt-1.5">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Konfirmasi Kata Sandi Baru <span class="text-rose-400">*</span>
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                   autocomplete="new-password" placeholder="Ulangi kata sandi baru..." />
            @if(isset($errors) && $errors->updatePassword->has('password_confirmation'))
                <p class="text-rose-400 text-xs mt-1.5">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="pt-2 flex items-center gap-4">
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs uppercase tracking-wider shadow-lg shadow-sky-500/25 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Perbarui Kata Sandi</span>
            </button>
        </div>
    </form>
</section>
