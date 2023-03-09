<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DownloadInvoiceFromUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user_id;
    public $file_url;
    public $file_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $file_url,$file_name)
    { 
         $this->file_url=$file_url;
         $this->file_name=$file_name; 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        
        $invoice_exists=  Storage::disk('public')->exists("invoices_pdf/". $this->file_name);
          
        if($invoice_exists==true){

          Storage::disk('public')->delete("invoices_pdf/".$this->file_name);
        }


        $pdf = file_get_contents($this->file_url);
        
        Storage::disk('local')->put('public/invoices_pdf/'.$this->file_name, $pdf); 
        
    }
}
