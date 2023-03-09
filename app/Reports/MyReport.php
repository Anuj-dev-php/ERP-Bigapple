<?php
namespace App\Reports;
require_once dirname(__FILE__)."/koolreport/core/autoload.php";
require_once dirname(__FILE__)."/koolreport/laravel/Friendship.php";
// require_once dirname(__FILE__)."/../../vendor/koolreport/laravel/LaravelDataSource.php";

class MyReport extends \koolreport\KoolReport
{
    use \koolreport\laravel\Friendship;
    // By adding above statement, you have claim the friendship between two frameworks
    // As a result, this report will be able to accessed all databases of Laravel
    // There are no need to define the settings() function anymore
    // while you can do so if you have other datasources rather than those
    // defined in Laravel.
    

    protected function settings()
    {
        return array(
            "dataSources"=>array(
                "data"=>array(
                    "class"=>'\koolreport\datasources\ArrayDataSource',
                    "dataFormat"=>"table",
                    "data"=>array(
                        array("name","age","income"),
                        array("John",26,10000),
                        array("Marry",29,60000),
                        array("Peter",34,100000),
                        array("Donald",28,80000),
                    )
                )
            )
        );
    }

    

    function setup()
    {
        // Let say, you have "sale_database" is defined in Laravel's database settings.
        // Now you can use that database without any futher setitngs.
        $this->src("data")
        ->pipe($this->dataStore("data"));    
    }
}