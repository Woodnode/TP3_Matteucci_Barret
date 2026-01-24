<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FilmStatistic;
use Illuminate\Support\Facades\File;

class FilmStatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data_source.json');
        $jsonContent = File::get($jsonPath);
        $data = json_decode($jsonContent, true);

        foreach ($data['data'] as $filmData) {
            $filmId = $filmData['id'];
            $reviews = $filmData['reviews'] ?? [];

            if (empty($reviews)) {
                continue;
            }

            $totalVotes = 0;
            $weightedScoreSum = 0;

            foreach ($reviews as $review) {
                $score = (float) $review['score'];
                $votes = (int) $review['votes'];
                
                $totalVotes += $votes;
                $weightedScoreSum += ($score * $votes);
            }

            $externalAverage = $totalVotes > 0 
                ? round($weightedScoreSum / $totalVotes, 2) 
                : 0.0;

            FilmStatistic::create([
                'film_id' => $filmId,
                'total_reviews' => 0, 
                'average_score' => 0.0, 
                'external_reviews' => $totalVotes,
                'external_average_score' => $externalAverage,
            ]);
        }
    }
}
