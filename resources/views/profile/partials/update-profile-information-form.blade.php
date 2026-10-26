<section>
    <header class="mb-6 pb-4 border-b border-slate-800 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-white flex items-center gap-2">
                <span>Informasi Profil & Foto</span>
                <span class="text-sky-400">👤</span>
            </h2>
            <p class="mt-1 text-xs text-slate-400">
                Perbarui foto avatar, data nama lengkap, dan informasi kontak Anda.
            </p>
        </div>
        <span class="text-[11px] px-2.5 py-1 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20 font-medium">
            Data Pribadi
        </span>
    </header>

    @if (session('status') === 'profile-updated')
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            <span>Profil dan foto Anda berhasil diperbarui!</span>
        </div>
    @endif

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Avatar Upload Section with Live Preview & Auto-Resize notice -->
        <div class="p-4 rounded-2xl bg-navy-950/70 border border-slate-800"
             x-data="{
                 previewUrl: '{{ $user->avatar ? asset(ltrim($user->avatar, '/')) : '' }}',
                 removeAvatar: false,
                 handleFileChange(event) {
                     const file = event.target.files[0];
                     if (file) {
                         this.previewUrl = URL.createObjectURL(file);
                         this.removeAvatar = false;
                     }
                 },
                 clearAvatar() {
                     this.previewUrl = '';
                     this.removeAvatar = true;
                     if (this.$refs.avatarInput) {
                         this.$refs.avatarInput.value = '';
                     }
                 }
             }">
            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-3">
                Foto Profil / Avatar
            </label>

            <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">

            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <!-- Preview Box -->
                <div class="relative group shrink-0">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" alt="{{ $user->name }}"
                             class="w-20 h-20 rounded-2xl object-cover border-2 border-sky-400 shadow-xl shadow-sky-500/20">
                    </template>
                    <template x-if="!previewUrl">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-sky-400 via-sky-500 to-blue-600 flex items-center justify-center text-navy-950 font-black text-2xl shadow-xl shadow-sky-500/20">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </template>
                </div>

                <!-- Upload Input & Helper -->
                <div class="space-y-2 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <label for="avatar_file"
                               class="px-4 py-2 rounded-xl bg-navy-850 hover:bg-navy-800 text-sky-300 hover:text-white border border-sky-500/30 hover:border-sky-400 font-semibold text-xs transition cursor-pointer flex items-center gap-2">
                            <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Pilih Foto Baru</span>
                        </label>
                        <input id="avatar_file" x-ref="avatarInput" name="avatar_file" type="file"
                               accept="image/png,image/jpeg,image/jpg,image/webp,image/gif"
                               @change="handleFileChange($event)" class="hidden">

                        <button type="button" x-show="previewUrl" @click="clearAvatar()"
                                class="px-3.5 py-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-semibold transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus Foto</span>
                        </button>
                    </div>

                    <div class="text-[11px] text-slate-400 leading-relaxed">
                        <span class="text-sky-400 font-semibold">⚡ Auto-Resize Aktif:</span>
                        Foto dari kamera HP/DSLR berukuran besar akan otomatis di-resize dan dikompresi menjadi format WebP beresolusi optimal. (Format: JPG, PNG, WEBP, maks 12 MB).
                    </div>
                </div>
            </div>

            @if(isset($errors) && $errors->has('avatar_file'))
                <p class="text-rose-400 text-xs mt-2">{{ $errors->first('avatar_file') }}</p>
            @endif
        </div>

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Nama Lengkap <span class="text-rose-400">*</span>
            </label>
            <input id="name" name="name" type="text"
                   class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @if(isset($errors) && $errors->has('name'))
                <p class="text-rose-400 text-xs mt-1.5">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Alamat Email <span class="text-rose-400">*</span>
            </label>
            <input id="email" name="email" type="email"
                   class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                   value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @if(isset($errors) && $errors->has('email'))
                <p class="text-rose-400 text-xs mt-1.5">{{ $errors->first('email') }}</p>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs space-y-2">
                    <p>
                        Alamat email Anda belum terverifikasi.
                        <button form="send-verification" class="underline text-sky-400 hover:text-sky-300 font-semibold focus:outline-none">
                            Kirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="font-medium text-emerald-400">
                            Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Nomor Telepon / WhatsApp -->
        <div>
            <label for="phone" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Nomor WhatsApp / HP
            </label>
            <input id="phone" name="phone" type="text"
                   class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                   value="{{ old('phone', $user->phone) }}" placeholder="0812xxxxxxxx" />
            @if(isset($errors) && $errors->has('phone'))
                <p class="text-rose-400 text-xs mt-1.5">{{ $errors->first('phone') }}</p>
            @endif
        </div>

        <!-- Bio / Catatan Profil -->
        <div>
            <label for="bio" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                Bio / Tentang Saya
            </label>
            <textarea id="bio" name="bio" rows="2"
                      class="w-full px-4 py-3 rounded-xl bg-navy-950 border border-slate-700/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-500/20 text-sm transition"
                      placeholder="Ceritakan sedikit tentang Anda atau preferensi liburan...">{{ old('bio', $user->bio) }}</textarea>
            @if(isset($errors) && $errors->has('bio'))
                <p class="text-rose-400 text-xs mt-1.5">{{ $errors->first('bio') }}</p>
            @endif
        </div>

        <div class="pt-2 flex items-center gap-4">
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-sky-400 hover:from-sky-400 hover:to-sky-300 text-navy-950 font-bold text-xs uppercase tracking-wider shadow-lg shadow-sky-500/25 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</section>
