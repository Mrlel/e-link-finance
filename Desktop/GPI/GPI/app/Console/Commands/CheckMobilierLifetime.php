<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mobilier;
use App\Models\User;
use App\Notifications\MobilierFinVieNotification;

class CheckMobilierLifetime extends Command
{
    protected $signature   = 'mobiliers:check-lifetime';
    protected $description = 'Vérifie les mobiliers approchant de leur fin de durée de vie et envoie des alertes';

    public function handle(): int
    {
        $this->info('🔍 Vérification des durées de vie des mobiliers...');

        $mobiliers = Mobilier::whereNotNull('date_fin_vie')
            ->where('date_fin_vie', '<=', now()->addDays(30))
            ->where('alerte_fin_vie_envoyee', false)
            ->with('direction')
            ->get();

        if ($mobiliers->isEmpty()) {
            $this->info('✅ Aucun mobilier proche de sa fin de vie.');
            return 0;
        }

        $this->info("⚠️  {$mobiliers->count()} mobilier(s) approchant de leur fin de vie.");

        foreach ($mobiliers as $mobilier) {
            $admins = User::where('direction_id', $mobilier->direction_id)
                ->where('role', 'admin')
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new MobilierFinVieNotification($mobilier));
            }

            $mobilier->update(['alerte_fin_vie_envoyee' => true]);
            $this->line("  → Alerte envoyée pour : {$mobilier->designation}");
        }

        $this->info('✅ Vérification terminée.');
        return 0;
    }
}
