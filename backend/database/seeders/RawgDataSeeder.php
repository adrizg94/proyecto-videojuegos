<?php

namespace Database\Seeders;

use App\Models\Creator;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Publisher;
use Illuminate\Database\Seeder;

class RawgDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $games = require database_path('seeders/data/games.php');
        $publishers = require database_path('seeders/data/publishers.php');
        $developers = require database_path('seeders/data/developers.php');
        $creators = require database_path('seeders/data/creators.php');

        foreach ($games as $game) {
            Game::updateOrCreate(
                ['rawg_id' => $game['rawg_id']],
                $game
            );
        }

        foreach ($publishers as $publisher) {
            Publisher::updateOrCreate(
                ['rawg_id' => $publisher['rawg_id']],
                $publisher
            );
        }

        foreach ($developers as $developer) {
            Developer::updateOrCreate(
                ['rawg_id' => $developer['rawg_id']],
                $developer
            );
        }

        foreach ($creators as $creator) {
            Creator::updateOrCreate(
                ['rawg_id' => $creator['rawg_id']],
                $creator
            );
        }
    }
}
