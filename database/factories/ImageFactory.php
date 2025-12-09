<?php

namespace Database\Factories;
use App\Models\Image;
use App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Obtiene un id válido de comentario
        $commentId = Comment::inRandomOrder()->value('id');

        return [
            'url' => fake()->imageUrl(),
            'comment_id' => $commentId
        ];
    }
}
