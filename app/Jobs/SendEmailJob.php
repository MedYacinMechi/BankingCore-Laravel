<?php

namespace App\Jobs;

use App\Mail\PasswordChangeOtp;
use App\Mail\TransactionMail;
use App\Mail\TransferOTPMail;
use App\Mail\UserLoginOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $email = $this->details['email'];
        $type = $this->details['type'];

        unset($this->details['type'], $this->details['email']);

        if ($type == 'login_otp') {
            $data = new UserLoginOtp($this->details);
            Mail::to($email)->send($data);
        }elseif($type == 'user_transaction_mail'){
            $data = new TransactionMail($this->details);
            Mail::to($email)->send($data);
        }elseif ($type == 'password_change') {
            $data = new PasswordChangeOtp($this->details['confirmation_code']);
            Mail::to($email)->send($data);
        }else{
            $data = new TransferOTPMail($this->details);
            Mail::to($email)->send($data);
        }

    }
}
