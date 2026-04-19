<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DuelService;

class CheckDuelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duels:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprova els duels vençuts i els finalitza determinant el guanyador o empat';

    /**
     * Execute the console command.
     */
    public function handle(DuelService $duelService)
    {
        $this->info('Iniciant la comprovació de duels...');
        
        $duelService->resolveDuels();
        
        $this->info('Comprovació de duels finalitzada correctament.');
    }
}
