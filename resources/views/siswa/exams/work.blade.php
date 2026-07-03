<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Ujian - <?= e($exam->title) ?></title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen select-none" oncontextmenu="return false">

    <!-- OVERLAY MULAI -->
    <div id="overlay" class="fixed inset-0 z-[60] bg-gray-900 text-white flex items-center justify-center">
        <div class="text-center max-w-md px-6">
            <i class="fa-solid fa-shield-halved text-5xl text-green-400 mb-4"></i>
            <h1 class="text-2xl font-bold mb-2"><?= e($exam->title) ?></h1>
            <p class="text-gray-300 text-sm mb-1"><?= (int) $exam->duration ?> menit • <?= $questions->count() ?> soal
            </p>
            <div class="text-left bg-white/10 rounded-xl p-4 my-4 text-sm space-y-1">
                <p class="font-semibold text-amber-300"><i class="fa-solid fa-triangle-exclamation"></i> Aturan Ujian:
                </p>
                <p>• Dilarang pindah tab / keluar layar penuh.</p>
                <p>• Klik kanan & copy-paste dinonaktifkan.</p>
                <p>• Pelanggaran ke-3 → ujian otomatis dikumpulkan.</p>
            </div>
            <button id="btnStart" class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-xl font-semibold w-full"><i
                    class="fa-solid fa-play"></i> Masuk Mode Ujian</button>
        </div>
    </div>

    <!-- TOP BAR -->
    <header class="fixed top-0 inset-x-0 z-40 bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
            <div class="min-w-0">
                <p class="font-bold text-gray-800 truncate text-sm"><?= e($exam->title) ?></p>
                <p class="text-xs text-gray-400 truncate"><?= e($exam->subject->name ?? '') ?></p>
            </div>
            <div class="flex items-center gap-3">
                <span id="violasiBadge"
                    class="hidden text-xs font-semibold px-2.5 py-1 rounded-full bg-red-100 text-red-600"><i
                        class="fa-solid fa-triangle-exclamation"></i> <span id="violasiCount">0</span>/3</span>
                <div id="timer"
                    class="font-mono font-bold text-gray-800 bg-gray-100 px-3 py-1.5 rounded-lg text-sm"><i
                        class="fa-regular fa-clock text-green-600"></i> --:--</div>
                <button onclick="konfirmasiSelesai()"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-1.5 rounded-lg"><i
                        class="fa-solid fa-flag-checkered"></i> Selesai</button>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 pt-20 pb-28 grid grid-cols-1 lg:grid-cols-4 gap-6">
        <section class="lg:col-span-3 space-y-4">
            <?php $no = 0; ?>
            @foreach ($questions as $q)
                <?php $no++; ?>
                <div class="soal <?= $no === 1 ? '' : 'hidden' ?> bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                    data-index="<?= $no ?>">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-semibold text-green-600">Soal <?= $no ?> /
                            <?= $questions->count() ?></span>
                        <span
                            class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500"><?= ucfirst(str_replace('_', ' ', $q->type)) ?></span>
                    </div>
                    <div class="text-gray-800 mb-5 leading-relaxed"><?= $q->question ?></div>

                    @if ($q->type === 'pilihan_ganda')
                        <?php
                        $opts = [];
                        foreach (['a', 'b', 'c', 'd', 'e'] as $l) {
                            $v = $q->{'option_' . $l};
                            if ($v !== null && $v !== '') {
                                $opts[$l] = $v;
                            }
                        }
                        if (!empty($exam->shuffle_options)) {
                            $keys = array_keys($opts);
                            shuffle($keys);
                            $tmp = [];
                            foreach ($keys as $k) {
                                $tmp[$k] = $opts[$k];
                            }
                            $opts = $tmp;
                        }
                        $cur = $answers[$q->id] ?? null;
                        ?>
                        <div class="space-y-2.5">
                            @foreach ($opts as $letter => $text)
                                <label
                                    class="flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-green-400 hover:bg-green-50 cursor-pointer transition">
                                    <input type="radio" name="q_<?= $q->id ?>" value="<?= $letter ?>"
                                        <?= $cur === $letter ? 'checked' : '' ?>
                                        onchange="simpanJawaban(<?= $q->id ?>, this.value, <?= $no ?>)"
                                        class="mt-1 text-green-600 focus:ring-green-500">
                                    <span><span class="font-semibold uppercase mr-1"><?= $letter ?>.</span>
                                        <?= e($text) ?></span>
                                </label>
                            @endforeach
                        </div>
                    @elseif ($q->type === 'coding')
                        <textarea name="q_<?= $q->id ?>" oninput="simpanJawabanDebounce(<?= $q->id ?>, this.value, <?= $no ?>)"
                            spellcheck="false"
                            class="w-full h-64 font-mono text-sm bg-gray-900 text-green-100 rounded-xl p-4 border border-gray-700 focus:ring-green-500"
                            placeholder="Tulis kode di sini..."><?= $answers[$q->id] ?? ($q->starter_code ?? '') ?></textarea>
                        <p class="text-xs text-gray-400 mt-2">Bahasa: <?= e($q->language ?? '-') ?></p>
                    @else
                        <textarea name="q_<?= $q->id ?>" oninput="simpanJawabanDebounce(<?= $q->id ?>, this.value, <?= $no ?>)"
                            class="w-full h-40 rounded-xl border border-gray-200 p-4 focus:ring-green-500" placeholder="Tulis jawaban Anda..."><?= $answers[$q->id] ?? '' ?></textarea>
                    @endif

                    <div class="flex justify-between mt-6">
                        <button onclick="pindah(<?= $no - 1 ?>)"
                            class="text-sm px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 <?= $no === 1 ? 'invisible' : '' ?>"><i
                                class="fa-solid fa-arrow-left"></i> Sebelumnya</button>
                        <button onclick="pindah(<?= $no + 1 ?>)"
                            class="text-sm px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 <?= $no === $questions->count() ? 'invisible' : '' ?>">Berikutnya
                            <i class="fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>
            @endforeach
        </section>

        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 lg:sticky lg:top-20">
                <p class="font-semibold text-gray-800 text-sm mb-3">Navigasi Soal</p>
                <div class="grid grid-cols-5 gap-2">
                    <?php for ($i = 1; $i <= $questions->count(); $i++): ?>
                    <button id="nav-<?= $i ?>" onclick="pindah(<?= $i ?>)"
                        class="nav-btn h-9 rounded-lg text-sm font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50"><?= $i ?></button>
                    <?php endfor; ?>
                </div>
                <div class="mt-4 space-y-1.5 text-xs text-gray-500">
                    <p><span class="inline-block w-3 h-3 rounded bg-green-500 mr-1.5"></span> Sudah dijawab</p>
                    <p><span class="inline-block w-3 h-3 rounded border border-gray-300 mr-1.5"></span> Belum dijawab
                    </p>
                </div>
                <button onclick="konfirmasiSelesai()"
                    class="w-full mt-5 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl text-sm"><i
                        class="fa-solid fa-flag-checkered"></i> Selesai & Kumpulkan</button>
            </div>
        </aside>
    </main>

    <form id="formSubmit" method="POST" action="<?= route('siswa.exams.submit', $exam) ?>" class="hidden">@csrf</form>

    <div id="modalWarn" class="hidden fixed inset-0 z-[70] bg-black/50 flex items-center justify-center px-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center">
            <i class="fa-solid fa-triangle-exclamation text-5xl text-red-500 mb-3"></i>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Peringatan Pelanggaran!</h3>
            <p id="modalWarnText" class="text-sm text-gray-500 mb-4"></p>
            <button onclick="tutupModal()"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded-xl w-full">Saya
                Mengerti, Lanjut</button>
        </div>
    </div>

    <!-- MODAL KONFIRMASI SELESAI -->
    <div id="modalSelesai" class="hidden fixed inset-0 z-[70] bg-black/50 flex items-center justify-center px-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center">
            <i class="fa-solid fa-flag-checkered text-4xl text-green-600 mb-3"></i>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Kumpulkan Ujian?</h3>
            <p class="text-sm text-gray-500 mb-5">Jawaban tidak bisa diubah lagi setelah dikumpulkan.</p>
            <div class="flex gap-3">
                <button onclick="tutupSelesai()"
                    class="flex-1 border border-gray-200 text-gray-700 hover:bg-gray-50 font-semibold py-2.5 rounded-xl">Batal</button>
                <button onclick="kumpulkanSekarang()"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl">Ya,
                    Kumpulkan</button>
            </div>
        </div>
    </div>

    <!-- OVERLAY AUTO-SUBMIT -->
    <div id="overlaySubmit"
        class="hidden fixed inset-0 z-[80] bg-gray-900/90 text-white flex items-center justify-center px-6 text-center">
        <div>
            <i class="fa-solid fa-circle-notch fa-spin text-4xl text-green-400 mb-3"></i>
            <p id="overlaySubmitText" class="font-semibold"></p>
        </div>
    </div>

    <script>
        const CFG = {
            remaining: <?= (int) $remaining ?>,
            total: <?= (int) $questions->count() ?>,
            answered: <?= json_encode(
                    (function () use ($questions, $answers) {
                        $r = [];
                        $i = 0;
                        foreach ($questions as $q) {
                            $i++;
                            $a = $answers[$q->id] ?? null;
                            $r[$i] = $a !== null && $a !== '';
                        }
                        return $r;
                    })(),
                ) ?>,
            answerUrl: "<?= route('siswa.exams.answer', $exam) ?>",
            violationUrl: "<?= route('siswa.exams.violation', $exam) ?>",
            csrf: document.querySelector('meta[name=csrf-token]').content,
        };

        let current = 1,
            violations = 0,
            bolehKeluar = false,
            sudahMulai = false;
        const soalEls = document.querySelectorAll('.soal');
        const navBtns = document.querySelectorAll('.nav-btn');

        function pindah(i) {
            if (i < 1 || i > CFG.total) return;
            current = i;
            soalEls.forEach(el => el.classList.toggle('hidden', parseInt(el.dataset.index) !== i));
            navBtns.forEach(b => b.classList.remove('ring-2', 'ring-green-500'));
            const nb = document.getElementById('nav-' + i);
            if (nb) nb.classList.add('ring-2', 'ring-green-500');
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }



        function tandaiNav(index, answered) {
            const nb = document.getElementById('nav-' + index);
            if (!nb) return;
            if (answered) nb.classList.add('bg-green-500', 'text-white', 'border-green-500');
            else nb.classList.remove('bg-green-500', 'text-white', 'border-green-500');
        }
        Object.entries(CFG.answered).forEach(([idx, val]) => tandaiNav(parseInt(idx), val));

        function simpanJawaban(qid, val, index) {
            tandaiNav(index, val !== null && val !== '');
            fetch(CFG.answerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CFG.csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    question_id: qid,
                    answer: val
                })
            }).catch(() => {});
        }
        let debTimers = {};

        function simpanJawabanDebounce(qid, val, index) {
            clearTimeout(debTimers[qid]);
            debTimers[qid] = setTimeout(() => simpanJawaban(qid, val, index), 700);
        }

        let sisa = CFG.remaining;
        const timerEl = document.getElementById('timer');

        function fmt(s) {
            const h = Math.floor(s / 3600),
                m = Math.floor((s % 3600) / 60),
                d = s % 60;
            const pad = n => String(n).padStart(2, '0');
            return (h > 0 ? pad(h) + ':' : '') + pad(m) + ':' + pad(d);
        }

        function tickTimer() {
            if (sisa <= 0) {
                autoSubmit('Waktu habis!');
                return;
            }
            sisa--;
            timerEl.innerHTML = '<i class="fa-regular fa-clock text-green-600"></i> ' + fmt(sisa);
            if (sisa <= 60) timerEl.classList.add('text-red-600', 'animate-pulse');
        }
        tickTimer();
        setInterval(tickTimer, 1000);

        function konfirmasiSelesai() {
            document.getElementById('modalSelesai').classList.remove('hidden');
        }

        function tutupSelesai() {
            document.getElementById('modalSelesai').classList.add('hidden');
        }

        function kumpulkanSekarang() {
            bolehKeluar = true;
            document.getElementById('formSubmit').submit();
        }

        function autoSubmit(alasan) {
            bolehKeluar = true;
            const t = document.getElementById('overlaySubmitText');
            if (t) t.textContent = alasan + ' Ujian dikumpulkan otomatis...';
            document.getElementById('overlaySubmit').classList.remove('hidden');
            setTimeout(() => document.getElementById('formSubmit').submit(), 1200);
        }

        ['copy', 'paste', 'cut'].forEach(ev => document.addEventListener(ev, e => e.preventDefault()));
        document.addEventListener('keydown', e => {
            if (e.key === 'F12' || (e.ctrlKey && ['c', 'v', 'x', 'u', 's', 'p'].includes(e.key.toLowerCase()))) e
                .preventDefault();
        });

        function catatPelanggaran(alasan) {
            fetch(CFG.violationUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CFG.csrf,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            }).then(r => r.json()).then(d => {
                if (!d.ok) return;
                const badge = document.getElementById('violasiBadge');
                badge.classList.remove('hidden');
                document.getElementById('violasiCount').textContent = d.count;
                if (d.submitted) {
                    autoSubmit('Terlalu banyak pelanggaran (' + d.count + 'x).');
                    return;
                }
                document.getElementById('modalWarnText').textContent = alasan + ' Pelanggaran ke-' + d.count +
                    ' dari ' + d.limit + '.';
                document.getElementById('modalWarn').classList.remove('hidden');
            }).catch(() => {});
        }

        function tutupModal() {
            document.getElementById('modalWarn').classList.add('hidden');
        }
        // ===== ANTI-CONTEK: deteksi keluar (tab / aplikasi / fullscreen) =====
        let abaikanFokus = false; // supaya dialog kita sendiri tidak dihitung
        let lastViolation = 0;

        function deteksiKeluar(alasan) {
            if (!sudahMulai || bolehKeluar || abaikanFokus) return;
            const now = Date.now();
            if (now - lastViolation < 1200) return; // debounce (blur + visibilitychange bisa dobel)
            lastViolation = now;
            catatPelanggaran(alasan);
        }

        document.getElementById('btnStart').addEventListener('click', () => {
            const el = document.documentElement;
            if (el.requestFullscreen) el.requestFullscreen().catch(() => {});
            document.getElementById('overlay').classList.add('hidden');
            abaikanFokus = true;
            sudahMulai = true;
            setTimeout(() => {
                abaikanFokus = false;
            }, 1000); // grace 1 detik
            pindah(1);
        });

        // pindah tab di dalam browser
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) deteksiKeluar('Anda meninggalkan halaman ujian.');
        });

        // ALT+Tab / pindah aplikasi / split-screen / klik luar jendela
        window.addEventListener('blur', () => deteksiKeluar('Anda berpindah ke jendela/aplikasi lain.'));

        // keluar dari layar penuh
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) deteksiKeluar('Anda keluar dari mode layar penuh.');
        });

        window.addEventListener('beforeunload', e => {
            if (!bolehKeluar) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>

</html>
