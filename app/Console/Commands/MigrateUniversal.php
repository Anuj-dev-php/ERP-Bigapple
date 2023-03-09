<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helper\Helper;
use Illuminate\Support\Facades\Artisan;

class MigrateUniversal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'universal:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to execute migration on universal database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {  
        Artisan::call('migrate',array('--database'=>'default','--path'=>'database/migrations/universalmigrations')); 
        
        $this->info( Artisan::output());
            
        return 0;
    }
}
