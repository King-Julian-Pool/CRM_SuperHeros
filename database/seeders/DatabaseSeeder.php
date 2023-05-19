<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\IncidentType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // IncidentType::factory()->count(10)->create();

        $incidentTypes = [
            [
                'libelle' => 'Incendie',
            ],
            [
                'libelle' => 'Accident routier',
            ],
            [
                'libelle' => 'Accident fluviale',
            ],
            [
                'libelle' => 'Accident aérien',
            ],
            [
                'libelle' => 'Eboulement',
            ],
            [
                'libelle' => 'Invasion de serpent',
            ],
            [
                'libelle' => 'Fuite de gaz',
            ],
            [
                'libelle' => 'Manifestation',
            ],
            [
                'libelle' => 'Braquage',
            ],
            [
                'libelle' => 'Evasion d’un prisonnier',
            ],
        ];

        foreach ($incidentTypes as $incidentType) {
            IncidentType::create($incidentType);
        }
    }
}
