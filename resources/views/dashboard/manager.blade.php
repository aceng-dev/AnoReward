<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manager Dashboard – AnoReward</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --maroon:        #780000;
            --maroon-dark:   #560000;
            --maroon-light:  #9a0000;
            --maroon-faint:  #fff0f0;
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .btn-maroon {
            background-color: var(--maroon);
            color: #fff;
            transition: background-color .2s, transform .1s;
        }
        .btn-maroon:hover  { background-color: var(--maroon-dark); }
        .btn-maroon:active { transform: scale(.97); }

        .badge-done     { background:#fef9c3; color:#854d0e; }
        .badge-approved { background:#dcfce7; color:#166534; }

        /* Kelas helper untuk state error pada input */
        .input-base {
            width: 100%;
            border-radius: 0.75rem;
            border-width: 1px;
            border-style: solid;
            border-color: #e5e7eb; /* gray-200 default */
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .input-base:focus {
            outline: none;
            border-color: #780000;
            box-shadow: 0 0 0 3px rgba(120,0,0,.15);
        }
        .input-base::placeholder { color: #9ca3af; }
        .input-error { border-color: #f87171 !important; background-color: #fff1f2; }

        .modal-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 50;
            align-items: center;
            justify-content: center;
        }
        .modal-backdrop.open { display: flex; }
    </style>
</head>

<body class="min-h-screen bg-gray-50 text-gray-800">

{{-- ═══════════════════════════════════════════════════
     NAVBAR
════════════════════════════════════════════════════ --}}
<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background-color:var(--maroon)">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806
                             3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806
                             3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946
                             3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946
                             3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806
                             3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806
                             3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946
                             3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946
                             3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <span class="font-bold text-lg tracking-tight" style="color:var(--maroon)">AnoReward</span>
            <span class="hidden sm:inline text-gray-300 text-lg">|</span>
            <span class="hidden sm:inline text-sm font-medium text-gray-500">Project Manager</span>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">Manager</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="text-sm font-medium text-gray-500 hover:text-red-700 transition-colors px-3 py-1.5 rounded-lg hover:bg-red-50">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════
     KONTEN UTAMA
════════════════════════════════════════════════════ --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Selamat datang, {{ Auth::user()->name }} 👋</h1>
        <p class="mt-1 text-gray-500 text-sm">Kelola proyek, tetapkan tugas, dan nilai kinerja tim developer Anda.</p>
    </div>

    @if (session('success'))
    <div class="flex items-center gap-3 rounded-xl border px-5 py-4 text-sm font-medium"
         style="background:var(--maroon-faint); border-color:var(--maroon); color:var(--maroon-dark)">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="rounded-xl border border-red-300 bg-red-50 px-5 py-4 text-sm text-red-700 space-y-1">
        <p class="font-semibold">Terdapat kesalahan pada form:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════
         SECTION 1 — FORM BUAT PROYEK & TUGAS BARU
    ═════════════════════════════════════════════════ --}}
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3"
             style="background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%)">
            <div class="w-9 h-9 rounded-lg bg-white/20 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-bold text-white">Buat Proyek & Tugas Baru</h2>
                <p class="text-xs text-red-200 mt-0.5">Isi form di bawah untuk membuat proyek dan menugaskan task ke developer</p>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('manager.project.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama Proyek --}}
                    <div class="md:col-span-2">
                        <label for="project_name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Proyek <span class="text-red-500">*</span>
                        </label>
                        {{-- Gunakan class CSS murni, bukan Blade conditional di class --}}
                        <input
                            type="text"
                            id="project_name"
                            name="project_name"
                            value="{{ old('project_name') }}"
                            placeholder="cth: Pengembangan Fitur Notifikasi Real-time"
                            class="input-base {{ $errors->has('project_name') ? 'input-error' : '' }}" />
                        @error('project_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Tugas --}}
                    <div class="md:col-span-2">
                        <label for="task_name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nama Tugas <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="task_name"
                            name="task_name"
                            value="{{ old('task_name') }}"
                            placeholder="cth: Implementasi WebSocket pada endpoint /notifications"
                            class="input-base {{ $errors->has('task_name') ? 'input-error' : '' }}" />
                        @error('task_name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Assignee (Developer) --}}
                    <div>
                        <label for="developer_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Assignee (Developer) <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="developer_id"
                            name="developer_id"
                            class="input-base {{ $errors->has('developer_id') ? 'input-error' : '' }}">
                            <option value="" disabled selected>— Pilih Developer —</option>
                            @forelse ($developers as $dev)
                                <option value="{{ $dev->id }}" {{ old('developer_id') == $dev->id ? 'selected' : '' }}>
                                    {{ $dev->name }}
                                </option>
                            @empty
                                <option disabled>Belum ada developer terdaftar</option>
                            @endforelse
                        </select>
                        @error('developer_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deadline --}}
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Deadline <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="due_date"
                            name="due_date"
                            value="{{ old('due_date') }}"
                            class="input-base {{ $errors->has('due_date') ? 'input-error' : '' }}" />
                        @error('due_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tingkat Kesulitan --}}
                    <div>
                        <label for="difficulty" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Tingkat Kesulitan <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="difficulty"
                            name="difficulty"
                            class="input-base {{ $errors->has('difficulty') ? 'input-error' : '' }}">
                            <option value="" disabled selected>— Pilih Tingkat Kesulitan —</option>
                            <option value="Low"    {{ old('difficulty') === 'Low'    ? 'selected' : '' }}>🟢 Low</option>
                            <option value="Medium" {{ old('difficulty') === 'Medium' ? 'selected' : '' }}>🟡 Medium</option>
                            <option value="High"   {{ old('difficulty') === 'High'   ? 'selected' : '' }}>🔴 High</option>
                        </select>
                        @error('difficulty')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                            class="btn-maroon inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Proyek & Tugas
                    </button>
                </div>

            </form>
        </div>
    </section>


    {{-- ════════════════════════════════════════════════
         SECTION 2 — REVIEW & SCORING TUGAS SELESAI
    ═════════════════════════════════════════════════ --}}
    <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                     style="background:var(--maroon-faint)">
                    <svg class="w-5 h-5" style="color:var(--maroon)" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                 M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2
                                 m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-800">Review & Scoring Tugas</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Tugas dengan status <span class="font-semibold text-yellow-700">Done</span> menunggu penilaian Anda</p>
                </div>
            </div>
            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold badge-done">
                {{ $doneTasks->count() }} menunggu
            </span>
        </div>

        <div class="overflow-x-auto">
            @if ($doneTasks->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center text-gray-400">
                    <svg class="w-14 h-14 mb-4 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                 a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="font-semibold text-gray-500">Belum ada tugas yang siap dinilai</p>
                    <p class="text-sm mt-1">Tugas dengan status Done dari developer akan muncul di sini.</p>
                </div>
            @else
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3.5">Nama Tugas</th>
                            <th class="px-6 py-3.5">Proyek</th>
                            <th class="px-6 py-3.5">Developer</th>
                            <th class="px-6 py-3.5">Deadline</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($doneTasks as $task)
                        <tr class="hover:bg-gray-50 transition-colors">

                            <td class="px-6 py-4 font-medium text-gray-800 max-w-[200px]">
                                <p class="truncate" title="{{ $task->task_name }}">{{ $task->task_name }}</p>
                                @if ($task->description)
                                    <p class="text-xs text-gray-400 truncate mt-0.5" title="{{ $task->description }}">
                                        {{ $task->description }}
                                    </p>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $task->project->project_name ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                                         style="background:var(--maroon)">
                                        {{ strtoupper(substr($task->developer->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-gray-700">{{ $task->developer->name ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                @if ($task->due_date)
                                    <span class="{{ \Carbon\Carbon::today()->gt($task->due_date) ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $task->due_date->format('d M Y') }}
                                    </span>
                                    @if (\Carbon\Carbon::today()->gt($task->due_date))
                                        <span class="block text-xs text-red-400">
                                            {{ \Carbon\Carbon::today()->diffInDays($task->due_date) }} hari terlambat
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold badge-done">
                                    Done
                                </span>
                            </td>

                            {{--
                                FIX onclick: gunakan data-* attributes, bukan inline Blade di dalam onclick.
                                JS membaca data-task-id, data-task-name, data-dev-name saat tombol diklik.
                                Ini menghilangkan error "Property assignment expected" dari VS Code linter.
                            --}}
                            <td class="px-6 py-4 text-center">
                                <button
                                    type="button"
                                    data-task-id="{{ $task->id }}"
                                    data-task-name="{{ $task->task_name }}"
                                    data-dev-name="{{ $task->developer->name ?? 'N/A' }}"
                                    class="btn-nilai btn-maroon inline-flex items-center gap-1.5 rounded-lg px-3.5 py-1.5 text-xs font-semibold shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    Beri Nilai
                                </button>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </section>

</main>


{{-- ═══════════════════════════════════════════════════
     MODAL — FORM PENILAIAN TUGAS
════════════════════════════════════════════════════ --}}
<div id="modalBackdrop" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">

        <div class="px-6 py-5 flex items-center justify-between"
             style="background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%)">
            <div>
                <h3 id="modalTitle" class="text-base font-bold text-white">Beri Nilai Tugas</h3>
                <p id="modalSubtitle" class="text-xs text-red-200 mt-0.5">—</p>
            </div>
            <button id="btnCloseModal"
                    class="text-white/70 hover:text-white transition-colors rounded-lg p-1 hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="approveForm" method="POST" action="">
            @csrf

            <div class="px-6 py-6 space-y-5">

                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-sm">
                    <p class="text-gray-500 text-xs mb-1 font-medium uppercase tracking-wide">Tugas yang Dinilai</p>
                    <p id="modalTaskName" class="font-semibold text-gray-800">—</p>
                    <p id="modalDevName"  class="text-gray-500 text-xs mt-0.5">Developer: —</p>
                </div>

                <div>
                    <label for="pm_rating" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nilai Kualitas (1–100) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="pm_rating"
                        name="pm_rating"
                        min="1"
                        max="100"
                        placeholder="Masukkan nilai antara 1 hingga 100"
                        class="input-base" />
                    <p class="text-xs text-gray-400 mt-1.5">
                        Nilai ini akan dibobot <strong>60%</strong>. Sisanya <strong>40%</strong> dihitung otomatis dari ketepatan waktu.
                    </p>
                </div>

                <div class="rounded-xl border px-4 py-3 text-xs text-gray-500 space-y-1"
                     style="border-color:rgba(120,0,0,.2); background:var(--maroon-faint)">
                    <p class="font-semibold" style="color:var(--maroon)">Rumus Perhitungan Skor Akhir</p>
                    <p>Task Score = (Nilai Kualitas × 0.6) + (Poin Waktu × 0.4)</p>
                    <p class="text-gray-400">Poin Waktu: 100 jika tepat waktu, berkurang 10/hari jika terlambat (min. 0).</p>
                </div>

            </div>

            <div class="px-6 pb-6 flex gap-3 justify-end">
                <button type="button" id="btnCancelModal"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold
                               text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit"
                        class="btn-maroon inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Approve &amp; Lock Task
                </button>
            </div>

        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════ --}}
<script>
    const backdrop    = document.getElementById('modalBackdrop');
    const approveForm = document.getElementById('approveForm');
    const modalTask   = document.getElementById('modalTaskName');
    const modalDev    = document.getElementById('modalDevName');
    const modalSub    = document.getElementById('modalSubtitle');
    const ratingInput = document.getElementById('pm_rating');

    // ── Buka modal via data-* attributes (tidak ada Blade/JSON di onclick) ──
    document.querySelectorAll('.btn-nilai').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var taskId   = this.getAttribute('data-task-id');
            var taskName = this.getAttribute('data-task-name');
            var devName  = this.getAttribute('data-dev-name');

            approveForm.action    = '/manager/task/' + taskId + '/approve';
            modalTask.textContent = taskName;
            modalDev.textContent  = 'Developer: ' + devName;
            modalSub.textContent  = 'ID Tugas #' + taskId;
            ratingInput.value     = '';

            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
            setTimeout(function () { ratingInput.focus(); }, 100);
        });
    });

    // ── Tutup modal ──
    function closeModal() {
        backdrop.classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('btnCloseModal').addEventListener('click', closeModal);
    document.getElementById('btnCancelModal').addEventListener('click', closeModal);

    backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    // ── Validasi sebelum submit ──
    approveForm.addEventListener('submit', function (e) {
        var val = parseInt(ratingInput.value, 10);
        if (!val || val < 1 || val > 100) {
            e.preventDefault();
            ratingInput.classList.add('input-error');
            ratingInput.focus();
            return;
        }
        ratingInput.classList.remove('input-error');
    });
</script>

</body>
</html>