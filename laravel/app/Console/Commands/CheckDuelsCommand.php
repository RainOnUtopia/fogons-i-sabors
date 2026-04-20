<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DuelService;

class CheckDuelsCommand extends Command
{
    /**
     * Nom i signatura de la comanda de consola.
     *
     * @var string
     */
    protected $signature = 'duels:check';

    /**
     * Descripció de la comanda de consola.
     *
     * @var string
     */
    protected $description = 'Comprova els duels vençuts i els finalitza determinant el guanyador o empat';

    /**
     * Executar la comanda de consola.
     */
    public function handle(DuelService $duelService)
    {
        $this->info('Iniciant la comprovació de duels...');
        
        $duelService->resolveDuels();
        
        $this->info('Comprovació de duels finalitzada correctament.');
    }
}
