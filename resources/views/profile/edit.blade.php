<x-app-layout>
    @php
        $settingsActive = session('status') || $errors->any();
        $maxContribution = max(1, (int) ($contributions->max('total') ?? 0));
    @endphp

    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Akun SIDAK</p>
                    <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">Profil Pengguna</h1>
                </div>
                <a href="{{ route('konservasi.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-emerald-300 hover:text-emerald-800">
                    <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)_270px]">
                <aside class="h-fit rounded-xl border border-slate-200 bg-white p-5 shadow-sm" data-theme-surface>
                    <div class="flex flex-col items-center text-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-xl font-extrabold text-emerald-800 ring-4 ring-emerald-50/70">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <h2 class="mt-4 text-lg font-extrabold text-slate-900">{{ $user->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $user->role ?? 'Petugas Operator' }}</p>
                    </div>

                    <dl class="mt-6 space-y-4 border-t border-slate-100 pt-5 text-sm">
                        <div class="flex items-start gap-3">
                            <i class="fa-regular fa-envelope mt-0.5 w-4 text-emerald-700" aria-hidden="true"></i>
                            <div class="min-w-0">
                                <dt class="text-xs text-slate-400">Email</dt>
                                <dd class="break-all font-medium text-slate-700">{{ $user->email }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fa-regular fa-calendar mt-0.5 w-4 text-emerald-700" aria-hidden="true"></i>
                            <div>
                                <dt class="text-xs text-slate-400">Bergabung</dt>
                                <dd class="font-medium text-slate-700">{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</dd>
                            </div>
                        </div>
                    </dl>
                </aside>

                <section class="min-w-0">
                    <div class="border-b border-slate-200">
                        <nav class="flex gap-5 overflow-x-auto" role="tablist" aria-label="Menu profil">
                            <button type="button" class="profile-tab {{ $settingsActive ? '' : 'is-active' }}" id="tab-aktivitas" role="tab" aria-selected="{{ $settingsActive ? 'false' : 'true' }}" aria-controls="panel-aktivitas" data-profile-tab="aktivitas">
                                <i class="fa-solid fa-wave-square" aria-hidden="true"></i><span>Aktivitas</span>
                            </button>
                            <button type="button" class="profile-tab {{ $settingsActive ? 'is-active' : '' }}" id="tab-pengaturan" role="tab" aria-selected="{{ $settingsActive ? 'true' : 'false' }}" aria-controls="panel-pengaturan" data-profile-tab="pengaturan">
                                <i class="fa-solid fa-gear" aria-hidden="true"></i><span>Pengaturan</span>
                            </button>
                        </nav>
                    </div>

                    <div id="panel-aktivitas" class="profile-panel {{ $settingsActive ? 'hidden' : '' }} py-5" role="tabpanel" aria-labelledby="tab-aktivitas" data-profile-panel="aktivitas">
                        <div class="mb-5 flex items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Aktivitas pengguna</h2>
                                <p class="mt-1 text-sm text-slate-500">Data konservasi terbaru yang Anda input.</p>
                            </div>
                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-800">{{ $recordCount }} data</span>
                        </div>

                        @if ($records->isEmpty())
                            <div class="rounded-xl border border-dashed border-slate-300 bg-white px-5 py-12 text-center" data-theme-surface>
                                <i class="fa-regular fa-folder-open text-2xl text-slate-400" aria-hidden="true"></i>
                                <h3 class="mt-3 font-bold text-slate-800">Belum ada aktivitas</h3>
                                <p class="mt-1 text-sm text-slate-500">Data yang Anda input akan muncul di sini.</p>
                                <a href="{{ route('konservasi.create') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah data
                                </a>
                            </div>
                        @else
                            <ol class="divide-y divide-slate-200 rounded-xl border border-slate-200 bg-white px-5" data-theme-surface>
                                @foreach ($records as $record)
                                    <li class="flex gap-4 py-5">
                                        <span class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                                            <i class="fa-solid fa-file-circle-check text-sm" aria-hidden="true"></i>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                                                <h3 class="font-bold text-slate-900">{{ $record->subBidang?->nama_sub_bidang ?? 'Data konservasi' }}</h3>
                                                <time class="text-xs text-slate-500" datetime="{{ $record->created_at?->toIso8601String() }}">{{ $record->created_at?->translatedFormat('d M Y, H:i') }}</time>
                                            </div>
                                            <p class="mt-1 text-sm text-slate-600">
                                                {{ $record->subBidang?->bidang?->nama_bidang ?? 'Bidang tidak tersedia' }}
                                                <span class="mx-1 text-slate-300">·</span> Tahun {{ $record->tahun }}
                                                @if ($record->jumlah !== null)
                                                    <span class="mx-1 text-slate-300">·</span> Jumlah {{ number_format($record->jumlah) }}
                                                @endif
                                            </p>
                                            @if ($record->keterangan)
                                                <p class="mt-2 line-clamp-2 text-xs text-slate-500">{{ $record->keterangan }}</p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>

                    <div id="panel-pengaturan" class="profile-panel {{ $settingsActive ? '' : 'hidden' }} space-y-5 py-5" role="tabpanel" aria-labelledby="tab-pengaturan" data-profile-panel="pengaturan" @unless ($settingsActive) hidden @endunless>
                        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6" data-theme-surface>
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6" data-theme-surface>
                            @include('profile.partials.update-password-form')
                        </div>
                        <div class="rounded-xl border border-rose-200 bg-white p-5 sm:p-6" data-theme-surface>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </section>

                <aside class="space-y-5">
                    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" data-theme-surface>
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="font-bold text-slate-900">Kontribusi per bidang</h2>
                            <i class="fa-solid fa-chart-simple text-emerald-700" aria-hidden="true"></i>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Jumlah data yang Anda input.</p>
                        @if ($contributions->isEmpty())
                            <p class="mt-5 rounded-lg bg-slate-50 p-4 text-center text-sm text-slate-500">Belum ada data kontribusi.</p>
                        @else
                            <ul class="mt-5 space-y-4">
                                @foreach ($contributions as $contribution)
                                    <li>
                                        <div class="mb-1.5 flex items-center justify-between gap-2 text-xs">
                                            <span class="truncate font-semibold text-slate-700">{{ $contribution->nama_bidang }}</span>
                                            <span class="shrink-0 font-bold text-slate-900">{{ $contribution->total }}</span>
                                        </div>
                                        <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-emerald-600" style="width: {{ ($contribution->total / $maxContribution) * 100 }}%"></div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>

                    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm" data-theme-surface>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total kontribusi</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ number_format($recordCount) }}</p>
                        <p class="mt-1 text-sm text-slate-500">rekaman konservasi</p>
                    </section>
                </aside>
            </div>
        </div>
    </div>

    <style>
        .profile-tab {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            gap: 0.55rem;
            border-bottom: 2px solid transparent;
            padding: 0.85rem 0.15rem;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .profile-tab:hover,
        .profile-tab.is-active {
            color: #047857;
        }

        .profile-tab.is-active {
            border-color: #059669;
        }

        .profile-tab:focus-visible {
            outline: 2px solid #10b981;
            outline-offset: 3px;
        }

        html[data-theme="dark"] .profile-tab { color: #a2b5a8; }
        html[data-theme="dark"] .profile-tab:hover,
        html[data-theme="dark"] .profile-tab.is-active { color: #86efac; }
    </style>
    <script>
        document.querySelectorAll('[data-profile-tab]').forEach(function (tab) {
            tab.addEventListener('click', function () {
                const selected = tab.dataset.profileTab;
                document.querySelectorAll('[data-profile-tab]').forEach(function (item) {
                    const active = item === tab;
                    item.classList.toggle('is-active', active);
                    item.setAttribute('aria-selected', active ? 'true' : 'false');
                });
                document.querySelectorAll('[data-profile-panel]').forEach(function (panel) {
                    const active = panel.dataset.profilePanel === selected;
                    panel.classList.toggle('hidden', !active);
                    panel.hidden = !active;
                });
            });
        });
    </script>
</x-app-layout>
