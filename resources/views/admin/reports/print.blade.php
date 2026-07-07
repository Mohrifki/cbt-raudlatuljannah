<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Nilai - <?= e(optional($exam->subject)->name ?? 'Ujian') ?></title>
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

        .kop-text {
            flex: 1;
            line-height: 1.4;
        }

        .kop-text .l1 {
            font-size: 15px;
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
            margin: 8px 0 12px;
        }

        .info td {
            padding: 2px 4px;
            vertical-align: top;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 5px 6px;
        }

        table.data th {
            background: #f0f0f0;
            text-align: center;
        }

        .stat {
            margin-top: 10px;
            font-size: 11px;
        }

        .ttd-box {
            margin-top: 28px;
            text-align: right;
        }

        .ttd-inner {
            display: inline-block;
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
                <div class="l1">REKAP NILAI UJIAN</div>
                <div class="l2"><?= e(strtoupper($namaSekolah)) ?></div>
                <div class="l3">SEMESTER <?= e(strtoupper($semester)) ?> &middot; TAHUN PELAJARAN
                    <?= e($tahunPelajaran) ?></div>
            </div>
            <div class="logo-spacer"></div>
        </div>
        <hr class="garis">

        <table class="info" width="100%">
            <tr>
                <td width="130">MATA PELAJARAN</td>
                <td width="10">:</td>
                <td><?= e(optional($exam->subject)->name ?? '-') ?></td>
                <td width="90">TANGGAL</td>
                <td width="10">:</td>
                <td><?= e($tanggal) ?></td>
            </tr>
            <tr>
                <td>KELAS / TIPE</td>
                <td>:</td>
                <td><?= $exam->type === 'pilihan' ? 'Peminatan' : e($exam->classes->pluck('name')->join(', ')) ?></td>
                <td>RATA-RATA</td>
                <td>:</td>
                <td><?= $stats['rata'] ?></td>
            </tr>
        </table>

        <table class="data">
            <thead>
                <tr>
                    <th width="35">No.</th>
                    <th width="120">NIS</th>
                    <th>Nama Siswa</th>
                    <th width="70">Kelas</th>
                    <th width="90">Status</th>
                    <th width="55">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $i => $r)
                    <tr>
                        <td style="text-align:center"><?= $i + 1 ?></td>
                        <td><?= e($r->nis ?? '-') ?></td>
                        <td><?= e($r->name) ?></td>
                        <td style="text-align:center"><?= e($r->kelas ?? '-') ?></td>
                        <td style="text-align:center"><?= e($r->status_label) ?></td>
                        <td style="text-align:center"><?= $r->score !== null ? e($r->score) : '-' ?></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:14px">Tidak ada peserta.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="stat">
            Total Peserta: <?= $stats['total'] ?> &nbsp;|&nbsp; Selesai: <?= $stats['selesai'] ?> &nbsp;|&nbsp; Belum:
            <?= $stats['belum'] ?>
            &nbsp;|&nbsp; Tertinggi: <?= $stats['tertinggi'] ?> &nbsp;|&nbsp; Terendah: <?= $stats['terendah'] ?>
        </div>

        <div class="ttd-box">
            <div class="ttd-inner">
                Guru / Panitia Ujian<br><br><br><br>
                ( ______________________ )<br>
                NIP.
            </div>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
    </div>
</body>

</html>
