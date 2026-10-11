<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Equipement;
use App\Models\User;
use App\Notifications\EquipementFinVieNotification;
use Carbon\Carbon;

class CheckEquipementLifetime extends Command
{
    protected $signature = 'equipements:check-lifetime';
    protected $description = 'Vérifie les équipements approchant de leur fin de durée de vie et envoie des alertes';

    public function handle()
    {
        $this->info('🔍 Vérification des durées de vie des équipements...');

        // Seuil d'alerte : 30 jours avant la fin de vie
        $seuilAlerte = now()->addDays(30);

        $equipements = Equipement::whereNotNull('date_fin_vie')
            ->where('date_fin_vie', '<=', $seuilAlerte)
            ->where('alerte_fin_vie_envoyee', false)
            ->with('direction')
            ->get();

        if ($equipements->isEmpty()) {
            $this->info('✅ Aucun équipement proche de sa fin de vie.');
            return 0;
        }

        $this->info("⚠️  {$equipements->count()} équipement(s) approchant de leur fin de vie.");

        foreach ($equipements as $equipement) {
            // Notifier les admins de la direction
            $admins = User::where('direction_id', $equipement->direction_id)
                ->where('role', 'admin')
                ->get();

            foreach ($admins as $admin) {
                $admin->notify(new EquipementFinVieNotification($equipement));
            }

            // Marquer l'alerte comme envoyée
            $equipement->update(['alerte_fin_vie_envoyee' => true]);

            $this->line("  → Alerte envoyée pour : {$equipement->des_equipement} (N° {$equipement->numero_serie})");
        }

        $this->info('✅ Vérification terminée.');
        return 0;
    }
}
