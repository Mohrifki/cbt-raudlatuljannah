<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Daftar Hadir - <?= e(optional($exam->subject)->name ?? 'Ujian') ?></title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 24px;
            color: #000;
            font-size: 12px;
        }

        .sheet {
            max-width: 800px;
            margin: 0 auto;
        }

        .kop {
            display: flex;
            align-items: center;
            text-align: center;
        }

        .kop .logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }

        .kop .logo-spacer {
            width: 72px;
        }

        /* penyeimbang agar judul benar-benar di tengah */
        .kop-text {
            flex: 1;
            line-height: 1.4;
        }

        .kop-text .l1 {
            font-size: 15px;
            font-weight: bold;
        }

        .kop-text .l1b {
            font-size: 14px;
            font-weight: bold;
        }

        .kop-text .l2 {
            font-size: 15px;
            font-weight: bold;
            margin-top: 2px;
        }

        .kop-text .l3 {
            font-size: 12px;
        }

        .garis {
            border: none;
            border-top: 3px solid #000;
            margin: 8px 0 4px;
        }

        .garis-tipis {
            border: none;
            border-top: 1px solid #000;
            margin: 0 0 14px;
        }

        .info td {
            padding: 2px 4px;
            vertical-align: top;
        }

        table.peserta {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.peserta th,
        table.peserta td {
            border: 1px solid #000;
            padding: 5px 6px;
        }

        table.peserta th {
            background: #f0f0f0;
            text-align: center;
        }

        .ttd-cell {
            width: 160px;
        }

        .ket {
            margin-top: 12px;
            font-size: 11px;
        }

        .footer-area {
            display: flex;
            justify-content: space-between;
            margin-top: 24px;
        }

        .kotak-jumlah td {
            border: 1px solid #000;
            padding: 3px 6px;
            font-size: 11px;
        }

        .ttd-box {
            text-align: center;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .no-print button {
            padding: 8px 20px;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }
        }

        @page {
            size: A4 portrait;
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="sheet">

        <div class="kop">
            <img src="<?= asset('images/logo.png') ?>" class="logo" alt="Logo">
            <div class="kop-text">
                <div class="l1">DAFTAR HADIR PESERTA</div>
                <div class="l1b"><?= e($modeFull) ?> (<?= e($modeUjian) ?>)</div>
                <div class="l2"><?= e(strtoupper($namaSekolah)) ?></div>
                <div class="l3">SEMESTER <?= e(strtoupper($semester)) ?> &middot; TAHUN PELAJARAN
                    <?= e($tahunPelajaran) ?></div>
            </div>
            <div class="logo-spacer"></div>
        </div>
        <hr class="garis">
        <hr class="garis-tipis">

        <table class="info" width="100%">
            <tr>
                <td width="130">SEKOLAH</td>
                <td width="10">:</td>
                <td><?= e($namaSekolah) ?></td>
                <td width="110">MAPEL</td>
                <td width="10">:</td>
                <td><?= e(optional($exam->subject)->name ?? '-') ?></td>
            </tr>
            <tr>
                <td>KELAS</td>
                <td>:</td>
                <td><?= e($kelasLabel ?: '-') ?></td>
                <td>HARI / TANGGAL</td>
                <td>:</td>
                <td><?= e($hari) ?>, <?= e($tanggal) ?></td>
            </tr>
            <tr>
                <td>JAM</td>
                <td>:</td>
                <td><?= e($jam) ?></td>
                <td>RUANG / SESI</td>
                <td>:</td>
                <td>______ / ______</td>
            </tr>
        </table>

        <table class="peserta">
            <thead>
                <tr>
                    <th width="35">No.</th>
                    <th width="130">NIS</th>
                    <th>Nama Peserta</th>
                    <th width="90">Kelas</th>
                    <th class="ttd-cell">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $i => $s)
                    <tr>
                        <td style="text-align:center"><?= $i + 1 ?></td>
                        <td><?= e($s->nis ?? '-') ?></td>
                        <td><?= e($s->name) ?></td>
                        <td style="text-align:center"><?= e(optional($s->schoolClass)->name ?? '-') ?></td>
                        <td><?= $i + 1 ?>.</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:14px">Tidak ada peserta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="ket">
            <strong>Keterangan:</strong>
            <ol style="margin:4px 0 0 18px; padding:0">
                <li>Pengawas memberi tanda silang pada nama peserta yang tidak hadir.</li>
                <li>Daftar hadir diserahkan kepada panitia setelah ujian selesai.</li>
            </ol>
        </div>

        <div class="footer-area">
            <table class="kotak-jumlah">
                <tr>
                    <td>Jumlah Peserta Seharusnya Hadir</td>
                    <td>: <?= count($students) ?> orang</td>
                </tr>
                <tr>
                    <td>Jumlah Peserta Tidak Hadir</td>
                    <td>: ______ orang</td>
                </tr>
                <tr>
                    <td>Jumlah Peserta Hadir</td>
                    <td>: ______ orang</td>
                </tr>
            </table>

            <div class="ttd-box">
                Pengawas Ujian<br><br><br><br>
                ( ______________________ )<br>
                No._______________
            </div>
        </div>

    </div>

    <div class="no-print">
        <button onclick="window.print()">Cetak / Print</button>
    </div>
</body>

</html>
