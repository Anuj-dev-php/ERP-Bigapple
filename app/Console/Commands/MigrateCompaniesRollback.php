<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use App\Helper\Helper;
use Illuminate\Support\Facades\Artisan;
use App\Repositories\UserCompanyRepository;
use App\Models\Company;

class MigrateCompaniesRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:migrate-rollback';

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

        $dbs= Company::pluck('db_name');

        foreach($dbs as $db){
            $dbexists=Helper::checkDatabaseExists($db);

            if($dbexists==false){ 
                continue;
            }
 
            Helper::connectDatabaseByName($db);  
            Artisan::call('migrate:rollback',array('--path'=>'database/migrations/companiesmigrations'));

            $this->info( Artisan::output());
            
            $this->info("Companies Migration Rollback run successfully on ".$db);
        } 
        return 0;
    }
}
