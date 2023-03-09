<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Storage;
use Illuminate\Support\Facades\Log; 

class DownloadInvoiceFromCrystalServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $server_url;
    public $file_name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($serverurl,$given_filename)
    {
        $this->server_url=$serverurl;
        $this->file_name=$given_filename;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        stream_context_set_default(array(
            'ssl'                => array(
            'peer_name'          => 'generic-server',
            'verify_peer'        => FALSE,
            'verify_peer_name'   => FALSE,
            'allow_self_signed'  => FALSE
             )));

             
          $tempImage = tempnam(sys_get_temp_dir(), $this->file_name);
  

          copy(   $this->server_url, $tempImage);
        
          if(Storage::exists('public/invoices_pdf/'.$this->filename)){
              Storage::delete('public/invoices_pdf/'.$this->filename);
          }

         $path = Storage::putFileAs('public/invoices_pdf',     $tempImage,$this->file_name );



    }
}
