<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ProdukController;
use Illuminate\Http\Request;

class SyncDigiflazz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'digiflazz:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all categories and products from Digiflazz API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting Digiflazz synchronization...');
        
        $controller = new ProdukController();
        $request = new Request();
        
        $res = $controller->sync($request);
        
        if (isset($res['success']) && $res['success']) {
            $this->info($res['message']);
            return Command::SUCCESS;
        } else {
            $this->error($res['message'] ?? 'Unknown error occurred.');
            return Command::FAILURE;
        }
    }
}
