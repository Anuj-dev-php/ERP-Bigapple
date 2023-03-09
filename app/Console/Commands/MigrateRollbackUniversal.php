<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Helper\Helper;
use Illuminate\Support\Facades\Artisan;

class MigrateRollbackUniversal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'universal:migrate-rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Artisan::call('migrate:rollback',array('--database'=>'default','--path'=>'database/migrations/universalmigrations')); 
        
        $this->info( Artisan::output());
            
        return 0;

    }
}
