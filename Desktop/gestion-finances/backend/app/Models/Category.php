<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'type', 'color'])]
class Category extends Model
{
    use HasFactory;

    public const DEFAULTS = [
        ['name' => 'Alimentation', 'type' => 'expense', 'color' => '#dc2626'],
        ['name' => 'Logement', 'type' => 'expense', 'color' => '#9333ea'],
        ['name' => 'Transport', 'type' => 'expense', 'color' => '#2563eb'],
        ['name' => 'Santé', 'type' => 'expense', 'color' => '#0d9488'],
        ['name' => 'Loisirs', 'type' => 'expense', 'color' => '#ea580c'],
        ['name' => 'Factures', 'type' => 'expense', 'color' => '#65a30d'],
        ['name' => 'Autres dépenses', 'type' => 'expense', 'color' => '#6b7280'],
        ['name' => 'Salaire', 'type' => 'income', 'color' => '#16a34a'],
        ['name' => 'Freelance', 'type' => 'income', 'color' => '#0891b2'],
        ['name' => 'Autres revenus', 'type' => 'income', 'color' => '#84cc16'],
    ];

    public static function seedDefaultsFor(User $user): void
    {
        $now = now();

        $user->categories()->insert(array_map(
            fn (array $category) => [...$category, 'user_id' => $user->id, 'created_at' => $now, 'updated_at' => $now],
            self::DEFAULTS
        ));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }
}
