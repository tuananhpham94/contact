<?php

namespace App\Jobs;

use App\Company;
use App\UserHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmailToCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $noti = $this->notification;
        Mail::raw("Checkout new: " . UserHistory::find($noti->history_id)->user->name, function ($message) use ($noti) {

            $message->from('anhpt@traffic.net.nz', 'Anh');

            $message->to(Company::find($noti->company_id)->email);

        });
    }
}
