<?php

namespace App\Jobs;

use App\Company;
use App\UserHistory;
use Barryvdh\DomPDF\Facade as PDF;
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
        $history = UserHistory::find($noti->history_id);
        $company = Company::find($noti->company_id);
        $oldInfo = UserHistory::orderBy('created_at', 'desc')->skip(1)->take(1)->get()->first();
        $pdf = PDF::loadView('pdf/pdf', compact('history', 'company', 'oldInfo'));

        try {
            Mail::send('pdf/mail', array() , function ($message) use ($noti, $pdf, $company) {

                $message->from('tuananh191194@gmail.com', 'ContactApp Team');

                $message->to($company->email)->subject("A customer has changed their contact detail")->attachData($pdf->output(), "address.pdf");

            });
            if( count(Mail::failures()) > 0 ) {
                $error = true;
                $data = "Hey <!channel>, A job has failed :shit::shit::shit:, please find the detail below and manually send email to those company". "\n" .
                    ">User ID: " . $history->user->unique_id . "\n" . ">Email: " . $history->email . "\n" . ">Address: " . $history->address . "\n" . ">Phone: " . $history->tel
                    . "\n" . "Notification should go to: " . $company->legal_name . " with the email of: " . $company->email . "\n";
                slackIntegration($data, $error);
            }
        } catch (\Exception $e) {
            // return response showing failed emails
            $error = true;
            $data = "Hey <!channel>, A job has failed :shit::shit::shit:, please find the detail below and manually send email to those company". "\n" .
                ">User ID: " . $history->user->unique_id . "\n" . ">Email: " . $history->email . "\n" . ">Address: " . $history->address . "\n" . ">Phone: " . $history->tel
                . "\n" . "Notification should go to: " . $company->legal_name . " with the email of: " . $company->email . "\n" . "```Error " . $e->getMessage() . "```";
            slackIntegration($data, $error);
        }
    }

    public function failed(\Exception $e)
    {
        $noti = $this->notification;
        $history = UserHistory::find($noti->history_id);
        $company = Company::find($noti->company_id);

        // return response showing failed emails
        $error = true;
        $data = "Hey <!channel>, A job has failed :shit::shit::shit:, please find the detail below and manually send email to those company". "\n" .
            ">User ID: " . $history->user->unique_id . "\n" . ">Email: " . $history->email . "\n" . ">Address: " . $history->address . "\n" . ">Phone: " . $history->tel
            . "\n" . "Notification should go to: " . $company->legal_name . " with the email of: " . $company->email . "\n" . "```Error " . $e->getMessage() . "```";
        slackIntegration($data, $error);
    }
}
