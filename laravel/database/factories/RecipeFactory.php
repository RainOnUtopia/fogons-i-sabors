<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Risotto de safrà amb pa d\'or',
            'Wagyu infusionat amb tòfona',
            'Pastís de llimona deconstruït',
            'Consommé de bolets salvatges',
            'Tagine de xai especiat',
            'Bacallà negre amb glacejat de miso',
            'Pasta fresca amb botifarra de mar',
            'Peixet salmó amb fins herba verda',
        ];

        $chefs = ['Marco Pierre', 'Elena A.', 'Josep M.', 'Carme T.', 'David G.', 'Jordi L.'];
        $difficulties = ['fàcil', 'mitjà', 'difícil'];
        $allTags = ['ITALIÀ', 'SOFISTICAT', 'LUXE', 'VEGETARIA', 'FRESC', 'PEX', 'FRANCES', 'JAPONÈS', 'FUSION'];

        $chefName = $this->faker->randomElement($chefs);

        return [
            'title' => $this->faker->randomElement($titles),
            'description' => $this->faker->paragraph(3),
            'cooking_time' => $this->faker->numberBetween(20, 120),
            'difficulty' => $this->faker->randomElement($difficulties),
            'image' => null, // Sin imagen por defecto
            'tags' => $this->faker->randomElements($allTags, $this->faker->numberBetween(2, 4)),
            'ingredients' => [
                $this->faker->word() . ' ' . $this->faker->numerify('###g'),
                $this->faker->word() . ' ' . $this->faker->numerify('###ml'),
                $this->faker->word() . ' ' . $this->faker->numerify('###g'),
                $this->faker->word() . ' ' . $this->faker->numerify('###ml'),
                $this->faker->word() . ' ' . $this->faker->numerify('###g'),
            ],
            'chef_name' => $chefName,
            'chef_avatar' => null,
            'chef_notes' => $this->faker->sentence(),
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'user_id' => null,
        ];
    }
}
