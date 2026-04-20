<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Afegeix receptes de demostració.
     */
    public function run(): void
    {
        $authors = User::query()
            ->whereIn('email', [
                'test@example.com',
                'aina@fogons.local',
                'pau@fogons.local',
                'maria@fogons.local',
            ])
            ->get()
            ->keyBy('email');

        $recipes = [
            [
                'title' => 'Risotto de safran amb pa d\'or',
                'description' => 'Un risotto cremos infusionat amb safran, pa d\'or cruixent i lamines de tofona fresca.',
                'cooking_time' => 45,
                'difficulty' => 'difícil',
                'image' => 'recipes/demo/risotto.jpg',
                'tags' => ['ITALIA', 'LUXE', 'SOFISTICAT'],
                'ingredients' => [
                    '320g d\'arros Carnaroli',
                    '1.5L de brou de verdures',
                    '1g de brins de safran',
                    '100ml de vi blanc sec',
                    '60g de mantega',
                    '100g de parmigia ratllat',
                ],
                'steps' => [
                    'Infusiona el safran dins una mica de brou calent.',
                    'Sofregeix l arros i mulla amb el vi blanc.',
                    'Afegeix el brou a cullerots fins obtenir una textura cremosa.',
                ],
                'chef_notes' => 'El mantecat final amb la mantega ben freda dona la textura ideal.',
                'author_email' => 'aina@fogons.local',
            ],
            [
                'title' => 'Wagyu infusionat amb tofona',
                'description' => 'Carn de Wagyu cuita a baixa temperatura i acabada amb aromes de tofona negra.',
                'cooking_time' => 30,
                'difficulty' => 'mitjà',
                'image' => 'recipes/demo/wagyu.jpg',
                'tags' => ['SOFISTICAT', 'CARN', 'LUXE'],
                'ingredients' => [
                    '250g de wagyu',
                    '15g de tofona negra',
                    '50ml de salsa demiglace',
                    '200g de verduretes de temporada',
                ],
                'steps' => [
                    'Marca la carn a foc viu i acaba la coccio lentament.',
                    'Prepara la salsa amb la demiglace i la tofona.',
                    'Serveix amb les verdures saltades.',
                ],
                'chef_notes' => 'Deixa reposar la carn abans de servir-la.',
                'author_email' => 'pau@fogons.local',
            ],
            [
                'title' => 'Pastis de llimona deconstruit',
                'description' => 'Una versio moderna del classic pastis de llimona, amb textures contrastades.',
                'cooking_time' => 60,
                'difficulty' => 'difícil',
                'image' => 'recipes/demo/pastis.jpg',
                'tags' => ['POSTRES', 'CITRIC', 'SOFISTICAT'],
                'ingredients' => [
                    '200g de farina',
                    '100g de mantega freda',
                    '100g de sucre',
                    '4 ous grans',
                    '200ml de suc de llimona',
                ],
                'steps' => [
                    'Prepara una base cruixent amb farina i mantega.',
                    'Cou la crema de llimona fins que espesseixi.',
                    'Munta el postre per capes amb textures diferents.',
                ],
                'chef_notes' => 'La pell de llimona acabada de ratllar canvia totalment el resultat.',
                'author_email' => 'maria@fogons.local',
            ],
            [
                'title' => 'Consomme de bolets salvatges',
                'description' => 'Un consomme aromatic i transparent fet amb bolets salvatges i fons suau.',
                'cooking_time' => 90,
                'difficulty' => 'difícil',
                'image' => 'recipes/demo/consome.jpg',
                'tags' => ['SOPES', 'BOLETS', 'SOFISTICAT'],
                'ingredients' => [
                    '1.5kg de bolets salvatges',
                    '2L de fons de verdures',
                    '4 clares d\'ou',
                    '200g de verdures',
                ],
                'steps' => [
                    'Bull els bolets amb el fons de verdures.',
                    'Clarifica el brou amb les clares d ou.',
                    'Cola amb suavitat abans de servir.',
                ],
                'chef_notes' => 'Els moviments suaus mantenen la claredat del brou.',
                'author_email' => 'test@example.com',
            ],
            [
                'title' => 'Tagine de xai especiat',
                'description' => 'Estofat aromatic de xai amb comi, canyella i fruita seca.',
                'cooking_time' => 120,
                'difficulty' => 'mitjà',
                'image' => 'recipes/demo/tagine.jpg',
                'tags' => ['MARROQUI', 'ESPECIAT', 'CARN'],
                'ingredients' => [
                    '800g de xai',
                    '2 cebes',
                    '50g d\'orellanes',
                    '1 cullerada de comi',
                    '200ml de suc de taronja',
                ],
                'steps' => [
                    'Dora el xai i reserva.',
                    'Sofregeix la ceba amb les especies.',
                    'Tapa i deixa coure lentament fins que sigui melos.',
                ],
                'chef_notes' => 'Les especies s\'han de torrar lleugerament per alliberar aroma.',
                'author_email' => 'aina@fogons.local',
            ],
            [
                'title' => 'Bacalla negre amb glacejat de miso',
                'description' => 'Bacalla melos amb un glacejat suau de miso, gingebre i toc citric.',
                'cooking_time' => 25,
                'difficulty' => 'fàcil',
                'image' => 'recipes/demo/bacalla.jpg',
                'tags' => ['PEIX', 'JAPONES', 'SOFISTICAT'],
                'ingredients' => [
                    '2 talls de bacalla negre',
                    '50g de miso blanc',
                    '25ml de mirin',
                    '15g de gingebre fresc',
                    '1 llimona',
                ],
                'steps' => [
                    'Mescla el miso amb el mirin i el gingebre.',
                    'Glaceja el peix i cou lo al punt.',
                    'Acaba amb ratlladura de llimona abans de servir.',
                ],
                'chef_notes' => 'Controla la temperatura per evitar que el miso es torri massa.',
                'author_email' => 'pau@fogons.local',
            ],
        ];

        foreach ($recipes as $recipeData) {
            $author = $authors->get($recipeData['author_email']);

            Recipe::updateOrCreate(
                ['title' => $recipeData['title']],
                [
                    'description' => $recipeData['description'],
                    'cooking_time' => $recipeData['cooking_time'],
                    'difficulty' => $recipeData['difficulty'],
                    'image' => $recipeData['image'],
                    'tags' => $recipeData['tags'],
                    'ingredients' => $recipeData['ingredients'],
                    'steps' => $recipeData['steps'],
                    'chef_notes' => $recipeData['chef_notes'],
                    'chef_name' => $author?->name ?? 'Chef Anonim',
                    'chef_avatar' => $author?->avatar,
                    'user_id' => $author?->id,
                ]
            );
        }
    }
}
