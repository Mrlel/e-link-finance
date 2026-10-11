<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AppExpiration extends Command
{
    protected $signature = 'app:expire {date? : Date d\'expiration (format: YYYY-MM-DD)}';
    protected $description = 'Gérer la date d\'expiration de l\'application';

    public function handle()
    {
        if (!$this->argument('date')) {
            if (Storage::disk('local')->exists('system/expiration.txt')) {
                $date = Storage::disk('local')->get('system/expiration.txt');
                $this->info("Date d'expiration actuelle : " . $date);
            } else {
                $this->info("Aucune date d'expiration définie");
            }
            return;
        }

        try {
            $date = Carbon::parse($this->argument('date'))->format('Y-m-d');
            Storage::disk('local')->put('system/expiration.txt', $date);
            $this->info("✅ Date d'expiration définie au : " . $date);
        } catch (\Exception $e) {
            $this->error("❌ Format de date invalide. Utilisez YYYY-MM-DD");
        }
    }
}