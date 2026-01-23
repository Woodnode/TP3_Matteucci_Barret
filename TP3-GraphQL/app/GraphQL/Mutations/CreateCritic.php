<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Critic;
use App\Models\FilmStatistic;

final readonly class CreateCritic
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $filmId = (int) $args['film_id'];
        $userId = (int) $args['user_id']; 
        $score = (float) $args['score'];
        $comment = $args['comment'];

        Critic::create([
            'film_id' => $filmId,
            'user_id' => $userId, 
            'score' => $score,
            'comment' => $comment,
        ]);

        $internalCritics = Critic::where('film_id', $filmId)->get();
        $internalCount = $internalCritics->count();
        $internalAvg = $internalCount > 0 
            ? (float) $internalCritics->avg('score') 
            : 0.0;

        $existingStat = FilmStatistic::where('film_id', $filmId)->first();
        $externalCount = $existingStat 
            ? (int) ($existingStat->external_reviews ?? 0) 
            : 0;
        $externalAvg = $existingStat 
            ? (float) ($existingStat->external_average_score ?? 0.0) 
            : 0.0;

        $totalCount = $externalCount + $internalCount;
        $globalAvg = $totalCount > 0
            ? (($externalAvg * $externalCount) + ($internalAvg * $internalCount)) / $totalCount
            : 0.0;

        $stat = FilmStatistic::updateOrCreate(
            ['film_id' => $filmId],
            [
                'total_reviews' => $totalCount,
                'average_score' => round($globalAvg, 2),
                'external_reviews' => $externalCount,  
                'external_average_score' => $externalAvg, 
            ]
        );

        return $stat->fresh();
    }
}
