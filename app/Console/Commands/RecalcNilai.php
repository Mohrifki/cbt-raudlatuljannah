<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamAttempt;

class RecalcNilai extends Command
{
    protected $signature = 'cbt:recalc-nilai {--dry : Tampilkan perubahan tanpa menyimpan}';

    protected $description = 'Hitung ulang skor seluruh attempt ujian yang sudah dikumpulkan ke skala 0-100';

    public function handle(): int
    {
        $attempts = ExamAttempt::with(['answers.question', 'user', 'exam'])
            ->where('status', 'submitted')
            ->get();

        if ($attempts->isEmpty()) {
            $this->info('Tidak ada attempt berstatus submitted. Tidak ada yang dihitung ulang.');
            return self::SUCCESS;
        }

        $dry     = (bool) $this->option('dry');
        $berubah = 0;

        foreach ($attempts as $attempt) {
            $totalMax = 0;
            $totalGot = 0;

            foreach ($attempt->answers as $a) {
                $totalMax += (float) (optional($a->question)->score ?? 0);
                $totalGot += (float) ($a->score ?? 0);
            }

            $baru = $totalMax > 0 ? round($totalGot / $totalMax * 100, 2) : 0;
            $lama = (float) ($attempt->score ?? 0);

            if (abs($baru - $lama) > 0.001) {
                $berubah++;
                $this->line(sprintf(
                    '#%d  %s  |  %s  :  %s -> %s',
                    $attempt->id,
                    optional($attempt->user)->name ?? '?',
                    optional($attempt->exam)->title ?? '?',
                    rtrim(rtrim(number_format($lama, 2, '.', ''), '0'), '.'),
                    rtrim(rtrim(number_format($baru, 2, '.', ''), '0'), '.')
                ));

                if (!$dry) {
                    $attempt->update(['score' => $baru]);
                }
            }
        }

        if ($dry) {
            $this->newLine();
            $this->info("[DRY RUN] {$berubah} attempt AKAN diperbarui. Jalankan tanpa --dry untuk menyimpan.");
        } else {
            $this->newLine();
            $this->info("Selesai. {$berubah} attempt diperbarui ke skala 0-100 (dari total {$attempts->count()} attempt).");
        }

        return self::SUCCESS;
    }
}
