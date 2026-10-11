<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        // Initialize the OpenAI client
        $this->client = OpenAI::client(config('services.openai.api_key'));
    }

    public function askQuestion(string $question): array
    {
        // Analyze the intent of the question
        $intention = $this->analyzeIntent($question);

        if ($intention['type'] === 'database') {
            return $this->handleDatabaseQuery($intention['details']);
        }

        // Use OpenAI to respond to the question
        try {
            $response = $this->client->chat()->create([
                'model' => config('services.openai.model', 'gpt-3.5-turbo'), // Default to ChatGPT-3.5
                'messages' => [
                    ['role' => 'user', 'content' => $question]
                ],
                'max_tokens' => config('services.openai.max_tokens', 1000),
            ]);

            // Log the full response for debugging
            \Log::info("OpenAI Response: " . json_encode($response));

            return [
                'text' => $response->choices[0]->message->content
            ];
        } catch (\Exception $e) {
            \Log::error("OpenAI API Error: " . $e->getMessage());
            return ['error' => 'Je ne peux pas répondre à cette question.'];
        }
    }

    public function testConnection(): array
    {
        try {
            $response = $this->client->models()->list();
            return ['success' => true, 'models' => $response->data];
        } catch (\Exception $e) {
            \Log::error("OpenAI Connection Error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function analyzeIntent(string $question): array
    {
        // Use OpenAI to analyze the intent of the question
        try {
            $response = $this->client->chat()->create([
                'model' => config('services.openai.model', 'gpt-3.5-turbo'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Analyse cette question et retourne son intention.'],
                    ['role' => 'user', 'content' => $question]
                ],
                'max_tokens' => 100,
            ]);

            $intent = json_decode($response->choices[0]->message->content, true);

            return $intent; // Example: ['type' => 'database', 'details' => ['table' => 'users', 'action' => 'count']]
        } catch (\Exception $e) {
            \Log::error("Intent Analysis Error: " . $e->getMessage());
            return ['type' => 'error', 'details' => 'Impossible d\'analyser l\'intention.'];
        }
    }

    private function handleDatabaseQuery(array $details): array
    {
        try {
            $table = $details['table'] ?? null;
            $action = $details['action'] ?? null;
            $conditions = $details['conditions'] ?? [];

            if (!$table || !$action) {
                return ['error' => 'Table ou action non spécifiée.'];
            }

            // Check if the table exists in the database
            if (!\Schema::hasTable($table)) {
                return ['error' => "La table '$table' n'existe pas dans la base de données."];
            }

            $query = \DB::table($table);

            // Apply conditions if they exist
            foreach ($conditions as $field => $value) {
                if (\Schema::hasColumn($table, $field)) {
                    $query->where($field, $value);
                } else {
                    return ['error' => "La colonne '$field' n'existe pas dans la table '$table'."];
                }
            }

            // Handle specific actions
            switch ($action) {
                case 'count':
                    $count = $query->count();
                    return ['text' => "La table '$table' contient $count enregistrements."];
                case 'list':
                    $data = $query->get();
                    return ['text' => "Données de la table '$table' : " . $data->toJson()];
                case 'stats':
                    $field = $details['field'] ?? null;
                    if (!$field || !\Schema::hasColumn($table, $field)) {
                        return ['error' => "Le champ '$field' n'existe pas dans la table '$table'."];
                    }
                    $stats = $query->select($field, \DB::raw('count(*) as total'))
                        ->groupBy($field)
                        ->get();
                    return ['text' => "Statistiques pour '$field' dans la table '$table' : " . $stats->toJson()];
                default:
                    return ['error' => "Action '$action' non prise en charge."];
            }
        } catch (\Exception $e) {
            \Log::error("Database Query Error: " . $e->getMessage());
            return ['error' => 'Erreur lors de l\'exécution de la requête.'];
        }
    }
}