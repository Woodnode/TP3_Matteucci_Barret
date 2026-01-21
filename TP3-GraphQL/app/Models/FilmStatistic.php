<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilmStatistic extends Model
{
    protected $table = 'film_statistics';

    protected $fillable = [
        'film_id',
        'total_reviews',
        'average_score',
        'external_reviews',
        'external_average_score',
    ];

    protected $casts = [
        'total_reviews' => 'integer',
        'average_score' => 'decimal:2',
        'external_reviews' => 'integer',
        'external_average_score' => 'decimal:2',
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }
}
