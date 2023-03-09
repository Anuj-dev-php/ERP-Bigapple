<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Services\WhatsAppService;
use Illuminate\Support\Facades\Log; 

class SendWhatsappMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $template_id;
    public $mob_num ;
    public $first_name ;
    public $last_name ;
    public $gender ;
    public $pdf_link;
    public $db_name;
    public $txn_id;
    public $schedular_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($template_id,$mob_num,$first_name,$last_name,$gender,$pdf_link=NULL,$db_name=NULL,$txn_id=NULL,$schedular_id=NULL)
    { 

        $this->template_id=$template_id;

        $this->mob_num=$mob_num;

        $this->first_name=$first_name;

        $this->last_name=$last_name;

        $this->gender=$gender;

        $this->pdf_link=$pdf_link;
        
        $this->db_name=$db_name;

        $this->txn_id=$txn_id;

        $this->schedular_id=$schedular_id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $was=new WhatsAppService();

        $was->first_name=$this->first_name;
        $was->last_name=$this->last_name;
        $was->gender=$this->gender;
        $was->mob_num=$this->mob_num;
        $was->whatsapp_template_id=    $this->template_id;
        $was->pdf_link= $this->pdf_link;
        $was->db_name=$this->db_name;
        $was->txn_id=$this->txn_id;
        $was->schedular_id=$this->schedular_id; 

        $result= $was->getUserIdFromMobNumber(); 
        if(   $result['status']=="success"){ 
            // $was->pdf_link="https://www.africau.edu/images/default/sample.pdf";
             $result=$was->sendPdfLinkOnWhatsApp(); 
 
         } 

    }
}
