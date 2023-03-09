<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Services\EmailTranDataService;
use App\Helper\Helper;
use Illuminate\Support\Facades\Log; 
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use Swift_Attachment;
use App\Models\EmailSended;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $host;
    public $port;
    public $encryption;
    public $username;
    public $password;
    public $subject;
    public $from_name;
    public $from_email;
    public $to_email;
    public $body;
    public $filepath;
    public $showfilename;
    public $db_name;
    public $txn_id;
    public $schedular_id;

 


    public function __construct($host,$port,$encryption,$username,$password,$subject,$body,$fromname,$fromemail,$toemail,$filepath=NULL,$showfilename=NULL,$db_name=NULL,$txn_id=NULL,$schedular_id=NULL)
    {
      $this->host=$host;
      $this->port=$port;
      $this->encryption=$encryption;
      $this->username=$username;
      $this->password=$password;
      $this->subject=$subject;
      $this->from_name=$fromname;
      $this->from_email=$fromemail;
      $this->to_email=$toemail;
      $this->body=$body;
      $this->filepath=$filepath;
      $this->showfilename=$showfilename;
      $this->db_name=$db_name;
      $this->schedular_id=$schedular_id;
      $this->txn_id=$txn_id;

 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
 

      try {


       $to_email_array= explode(",",$this->to_email); 

        
          $transport = (new Swift_SmtpTransport($this->host,$this->port ,$this->encryption))
              ->setUsername($this->username)
              ->setPassword($this->password); 
              // 
              $mailer    = new Swift_Mailer($transport);

              $message   = (new Swift_Message($this->subject))
              ->setFrom($this->from_email,$this->from_name)
              ->setTo(    $to_email_array)
                ->setBody($this->body,'text/html');  

                if(!empty($this->filepath)  && !empty( $this->showfilename)){
                  $path = storage_path($this->filepath );
                  $message   = $message->attach(
                    Swift_Attachment::fromPath($path)->setFilename($this->showfilename)
                  );
                } 


                $mailer->send($message);  

               EmailSended::create(['to'=>$this->to_email,'subject'=>$this->subject,'body'=>substr($this->body, 0,500) ,'show_filename'=>$this->showfilename,'filepath'=>$this->filepath,'status'=>'processed','schedular_id'=>$this->schedular_id,'txn_id'=>$this->txn_id,'db_name'=>$this->db_name]);
              return true;
          } catch (\Swift_TransportException $e) {
             Log::info($e->getMessage());

               EmailSended::create(['to'=>$this->to_email,'subject'=>$this->subject,'body'=>substr($this->body, 0, 500)  ,'show_filename'=>$this->showfilename,'filepath'=>$this->filepath,'status'=>'failed','error_msg'=>substr($e->getMessage(),0,500),'schedular_id'=>$this->schedular_id,'txn_id'=>$this->txn_id,'db_name'=>$this->db_name]);
            
             return false;

          } catch (\Exception $e) {
             Log::info($e->getMessage()); 
              EmailSended::create(['to'=>$this->to_email,'subject'=>$this->subject,'body'=>substr($this->body, 0, 500)  ,'show_filename'=>$this->showfilename,'filepath'=>$this->filepath,'status'=>'failed','error_msg'=>substr($e->getMessage(),0,500),'schedular_id'=>$this->schedular_id,'txn_id'=>$this->txn_id,'db_name'=>$this->db_name]);
             
             return false;
          }   
    }
}
