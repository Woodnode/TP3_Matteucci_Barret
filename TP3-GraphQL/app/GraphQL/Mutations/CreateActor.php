<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Models\Actor;
use App\Models\Film;

final readonly class CreateActor
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $actor = Actor::create([
            'first_name' => $args['first_name'],
            'last_name' => $args['last_name'],
            'birthdate' => $args['birthdate'],
        ]);

        $actor->films()->attach($args['films']);
    
        if (!empty($args['film_images'])) 
        {
            foreach ($args['film_images'] as $imageData) 
            {
                Film::where('id', $imageData['film_id'])
                    ->update(['image' => $imageData['image_url']]);
            }
        }

        $actor->load('films');

        return $actor;
    }
}   
