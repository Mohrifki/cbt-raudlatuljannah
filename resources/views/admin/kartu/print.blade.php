<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kartu Ujian - Kelas <?= e($kelas->name) ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #111;
            background: #e5e7eb;
            padding: 8mm;
        }

        .toolbar {
            text-align: center;
            margin-bottom: 8mm;
        }

        .btn {
            background: #16a34a;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }

        .btn-close {
            background: #6b7280;
            margin-left: 8px;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 4mm;
            justify-content: flex-start;
        }

        /* Kartu ukuran A2 holder: 95mm x 65mm (landscape) */
        .card {
            width: 95mm;
            height: 65mm;
            border: 0.4mm solid #111;
            border-radius: 2mm;
            padding: 2.5mm;
            background: #fff;
            overflow: hidden;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
        }

        .head {
            display: flex;
            align-items: center;
            gap: 2mm;
            border-bottom: 0.5mm solid #111;
            padding-bottom: 1mm;
            margin-bottom: 1mm;
            flex: 0 0 auto;
        }

        .head .logo {
            width: 10mm;
            height: 10mm;
            object-fit: contain;
            flex: 0 0 auto;
        }

        .head .logo-box {
            width: 10mm;
            height: 10mm;
            border: 0.2mm dashed #bbb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4.5pt;
            color: #aaa;
            flex: 0 0 auto;
        }

        .head .logo-spacer {
            width: 10mm;
            flex: 0 0 auto;
        }

        .head .titles {
            flex: 1;
            text-align: center;
            line-height: 1.12;
        }

        .head .titles .t1 {
            font-size: 6pt;
            font-weight: bold;
        }

        .head .titles .t2 {
            font-size: 9pt;
            font-weight: bold;
        }

        .head .titles .t3 {
            font-size: 4.5pt;
            font-weight: bold;
        }

        .body {
            flex: 1 1 auto;
            display: flex;
            gap: 3mm;
            min-height: 0;
        }

        .photo {
            width: 20mm;
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            gap: 1mm;
        }

        .photo .pbox {
            width: 20mm;
            height: 25mm;
            border: 0.3mm solid #111;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fff;
        }

        .photo .pbox img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .photo .pbox svg {
            width: 12mm;
            height: 12mm;
            fill: #b9c0c9;
        }

        .photo .qr {
            width: 20mm;
            height: 16mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo .qr canvas,
        .photo .qr img {
            width: 15mm !important;
            height: 15mm !important;
        }

        .right {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 0;
        }

        .fields {
            font-size: 7.5pt;
            width: 100%;
        }

        .fields table {
            width: 100%;
            border-collapse: collapse;
        }

        .fields td {
            padding: 0.6mm 0;
            vertical-align: top;
            line-height: 1.2;
        }

        .fields td.k {
            width: 27mm;
        }

        .fields td.s {
            width: 3mm;
        }

        .fields td.v.user {
            font-weight: bold;
            font-size: 9pt;
            letter-spacing: 0.3pt;
        }

        .fields td.v.pass {
            font-weight: bold;
            font-style: italic;
            color: #b91c1c;
            font-size: 8pt;
        }

        .foot {
            display: flex;
            justify-content: flex-end;
        }

        .foot .sign {
            text-align: center;
            font-size: 6.5pt;
            width: 42mm;
            line-height: 1.2;
        }

        .foot .sign {
            position: relative;
        }

        .foot .sign .sp {
            height: 5mm;
        }

        .foot .sign .ttd {
            height: 17mm;
            width: auto;
            max-width: 38mm;
            object-fit: contain;
            display: block;
            margin: 0 auto -7mm;
            position: relative;
            z-index: 2;
        }

        .foot .sign .nm {
            font-weight: bold;
            text-decoration: underline;
            position: relative;
            z-index: 1;
        }

        @page {
            size: A4;
            margin: 8mm;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .toolbar {
                display: none;
            }
        }
    </style>
</head>

<body>
    <?php
    // Nilai default agar tidak error bila controller belum dikirim variabelnya
    $sekolah = $sekolah ?? 'RAUDLATUL JANNAH';
    $jenisUjian = $jenisUjian ?? 'PENILAIAN SUMATIF AKHIR SEMESTER';
    $tahunAjaran = $tahunAjaran ?? '2026/2027';
    $sesi = $sesi ?? 'Sesi 1';
    $ruang = $ruang ?? '-';
    $kepsek = $kepsek ?? 'Lisya Romadloniyah, S.S, M.Pd.';
    $nip = $nip ?? '';
    
    // Pakai file logo yang tersedia di public/images (logo aplikasi bila ada)
    $logoFile = null;
    foreach (['images/logo-sekolah.png', 'images/logo.png', 'images/logo.jpg', 'images/logo.jpeg', 'images/logo.webp'] as $cand) {
        if (file_exists(public_path($cand))) {
            $logoFile = $cand;
            break;
        }
    }
    
    // Tanda tangan kepala sekolah (letakkan file di public/images/)
    $ttdFile = null;
    foreach (['images/ttd-kepsek.png', 'images/tanda-tangan.png', 'images/ttd.png'] as $cand) {
        if (file_exists(public_path($cand))) {
            $ttdFile = $cand;
            break;
        }
    }
    ?>

    <div class="toolbar">
        <button class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
        <button class="btn btn-close" onclick="window.close()">Tutup</button>
    </div>

    <div class="grid">
        @forelse ($siswa as $s)
            <?php
            $username = $s->email;
            // Password siswa = NIS (reset via: php artisan siswa:reset-password)
            $password = $s->nis ?: '-';
            $foto = $s->photo ? asset('storage/' . $s->photo) : null;
            ?>
            <div class="card">
                <div class="head">
                    @if ($logoFile)
                        <img class="logo" src="<?= asset($logoFile) ?>" alt="logo">
                    @else
                        <div class="logo-box">LOGO</div>
                    @endif
                    <div class="titles">
                        <div class="t1">KARTU PESERTA <?= e($jenisUjian) ?></div>
                        <div class="t2"><?= e($sekolah) ?></div>
                        <div class="t3">TAHUN PELAJARAN <?= e($tahunAjaran) ?></div>
                    </div>
                    <div class="logo-spacer"></div>
                </div>

                <div class="body">
                    <div class="photo">
                        <div class="pbox">
                            @if ($foto)
                                <img src="<?= $foto ?>" alt="foto">
                            @else
                                <svg viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.4c-3.3 0-9.8 1.6-9.8 4.9v2.5h19.6v-2.5c0-3.3-6.5-4.9-9.8-4.9z" />
                                </svg>
                            @endif
                        </div>
                        <div class="qr" data-qr="<?= e($username) ?>"></div>
                    </div>

                    <div class="right">
                        <div class="fields">
                            <table>
                                <tr>
                                    <td class="k">No Peserta</td>
                                    <td class="s">:</td>
                                    <td class="v"><?= e($s->nis ?: '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="k">Nama</td>
                                    <td class="s">:</td>
                                    <td class="v"><?= e($s->name) ?></td>
                                </tr>
                                <tr>
                                    <td class="k">Kelas / Sesi Ujian</td>
                                    <td class="s">:</td>
                                    <td class="v"><?= e($kelas->name) ?> / <?= e($sesi) ?></td>
                                </tr>
                                <tr>
                                    <td class="k">Username</td>
                                    <td class="s">:</td>
                                    <td class="v user"><?= e($username) ?></td>
                                </tr>
                                <tr>
                                    <td class="k">Password</td>
                                    <td class="s">:</td>
                                    <td class="v pass"><?= e($password) ?>*</td>
                                </tr>
                                <tr>
                                    <td class="k">Ruang</td>
                                    <td class="s">:</td>
                                    <td class="v"><?= e($ruang) ?></td>
                                </tr>
                            </table>
                        </div>

                        <div class="foot">
                            <div class="sign">
                                Kepala Sekolah
                                @if ($ttdFile)
                                    <img class="ttd" src="<?= asset($ttdFile) ?>" alt="tanda tangan">
                                @else
                                    <div class="sp"></div>
                                @endif
                                <div class="nm"><?= e($kepsek ?: '.............................') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p style="width:100%; text-align:center; color:#666;">Tidak ada siswa di kelas ini.</p>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.querySelectorAll('.qr').forEach(function(el) {
            var val = el.getAttribute('data-qr') || '';
            if (!val) return;
            new QRCode(el, {
                text: val,
                width: 64,
                height: 64,
                correctLevel: QRCode.CorrectLevel.M
            });
        });

        // Otomatis buka dialog cetak setelah halaman & QR selesai dirender
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 600);
        });
    </script>
</body>

</html>
