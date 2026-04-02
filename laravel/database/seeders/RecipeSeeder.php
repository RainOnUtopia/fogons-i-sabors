<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear recetas específicas con datos reales
        Recipe::create([
            'title' => 'Risotto de safrà amb pa d\'or',
            'description' => 'Un risotto cremós infusionat amb safrà, pa d\'or cruixent i làmines de tòfona fresca. Un repte per als paladars més exigents.',
            'cooking_time' => 45,
            'difficulty' => 'difícil',
            'image' => null,
            'tags' => ['ITALIÀ', 'LUXE', 'SOFISTICAT'],
            'ingredients' => [
                '320g d\'arròs Carnaroli',
                '1.5L de brú de verdures, mantingut calent',
                '1g de brins de safrà premium',
                '100ml de vi blanc sec',
                '60g de mantega',
                '100g de parmigiano-reggiano, ratllat finament',
                '2 ceba xica, tallada finament',
            ],
            'chef_name' => 'Marco Pierre',
            'chef_avatar' => null,
            'chef_notes' => 'The secret to a perfect risotto is the temperature of the butter. It must be ice-cold when added at the end to create the perfect emulsion.',
            'rating' => 4.9,
        ]);

        Recipe::create([
            'title' => 'Wagyu infusionat amb tòfona',
            'description' => 'Carn de Wagyu tendra cuita a baja temperatura, infusionada amb aromes de tòfona negra i guarnida amb verduretes de temporada.',
            'cooking_time' => 30,
            'difficulty' => 'mitjà',
            'image' => null,
            'tags' => ['SOFISTICAT', 'CARNER', 'LUXE'],
            'ingredients' => [
                '250g de carn de Wagyu de primera qualitat',
                '15g de tòfona negra fresca',
                '50ml de salsa de demiglaçe',
                '200g de verduretes de temporada',
                'Sal i pebre de Maldon',
            ],
            'chef_name' => 'Elena A.',
            'chef_avatar' => null,
            'chef_notes' => 'Deixa reposar la carn almenys la meitat del temps que ha trigat a cuinar-se.',
            'rating' => 4.7,
        ]);

        Recipe::create([
            'title' => 'Pastís de llimona deconstruït',
            'description' => 'Una interpretació moderna del clàssic pastís de llimona, amb textures contrastants: merengue cru, crema de llimona i sablé de plàtan.',
            'cooking_time' => 60,
            'difficulty' => 'difícil',
            'image' => null,
            'tags' => ['POSTRES', 'CÍTRIC', 'SOFISTICAT'],
            'ingredients' => [
                '200g de flour',
                '100g de mantega freda',
                '100g de sucre',
                '4 ous grans',
                '200ml de suc de llimona fresc',
                'Prest de llimona',
            ],
            'chef_name' => 'Carme T.',
            'chef_avatar' => null,
            'chef_notes' => 'Utilitza sempre llimones frescas i de qualitat per al suc més intens.',
            'rating' => 4.6,
        ]);

        Recipe::create([
            'title' => 'Consommé de bolets salvatges',
            'description' => 'Un consommé transparent i aromàtic preparat amb bolets salvatges varietats, clar d\'ou i verdures aroàtiques.',
            'cooking_time' => 90,
            'difficulty' => 'difícil',
            'image' => null,
            'tags' => ['SOPES', 'SOFISTICAT', 'PEX'],
            'ingredients' => [
                '1.5kg de bolets salvatges varietats',
                '2L de fons de carn o verdura',
                '4 clars d\'ou',
                '200g de verdures per al clar',
                'Sal i pebre',
            ],
            'chef_name' => 'Josep M.',
            'chef_avatar' => null,
            'chef_notes' => 'La claretat és essencial. Els moviments lents i suaus mantindran la puretat del consommé.',
            'rating' => 4.8,
        ]);

        Recipe::create([
            'title' => 'Tagine de xai especiat',
            'description' => 'Estofat marroquí de xai amb aroma de comí, canyella, gengibre i préssec seca. Servit amb cuscús de safra.',
            'cooking_time' => 120,
            'difficulty' => 'mitjà',
            'image' => null,
            'tags' => ['MARROQUÍ', 'ESPECIAT', 'CARNER'],
            'ingredients' => [
                '800g de xai tallat a trossos',
                '2 ceboles mitjanes',
                '50g de préssec sec',
                '1 cullera de comí',
                '1/2 cullera de canyella',
                '200ml de suc de taronja',
            ],
            'chef_name' => 'Jordi L.',
            'chef_avatar' => null,
            'chef_notes' => 'Els espècies han de flotador suavment al principi de la cocció.',
            'rating' => 4.5,
        ]);

        Recipe::create([
            'title' => 'Bacallà negre amb glacejat de miso',
            'description' => 'Bacallà de qualitat negre cuita lentament amb salsa de miso, gengibre i cítric.',
            'cooking_time' => 25,
            'difficulty' => 'fàcil',
            'image' => null,
            'tags' => ['PEX', 'JAPONÈS', 'SOFISTICAT'],
            'ingredients' => [
                '2 trossos de bacallà negre de 150g cada un',
                '50g de miso blanc',
                '25ml de mirin',
                '15g de gengibre fresc',
                '1 llimona',
            ],
            'chef_name' => 'David G.',
            'chef_avatar' => null,
            'chef_notes' => 'El miso pot cremar si la temperatura puja massa. Mantén baixa i lenta la cocció.',
            'rating' => 4.7,
        ]);

        // Crear 2-3 més amb Factory
        Recipe::factory()->count(3)->create();
    }
}
