<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Film;
use App\Models\FilmStatistic;

class FilmStatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $path = database_path('data/data_source.json');
    $json = json_decode(File::get($path), true);

    $films = $json['data'] ?? [];

    foreach ($films as $filmData) {
        $filmId = $filmData['id'];

        if (!Film::whereKey($filmId)->exists()) {
            continue;
        }

        $scores = [];
        foreach (($filmData['reviews'] ?? []) as $review) {
            if (isset($review['score'])) {
                $scores[] = (float) $review['score'];
            }
        }

        $externalCount = count($scores);
        $externalAvg = $externalCount > 0
            ? array_sum($scores) / $externalCount
            : 0;

        FilmStatistic::updateOrCreate(
            ['film_id' => $filmId],
            [
                'external_reviews'        => $externalCount,
                'external_average_score'  => round($externalAvg, 2),
                'total_reviews'           => $externalCount,
                'average_score'           => round($externalAvg, 2),
            ]
        );
    }
}
}