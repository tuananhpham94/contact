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
        $pdf = PDF::loadView('pdf/pdf', compact('history'));
        $company = Company::find($noti->company_id);
        try {
            Mail::raw("Checkout new: " . $history->user->name, function ($message) use ($noti, $pdf, $company) {

                $message->from('anhpt@traffic.net.nz', 'Anh');

                $message->to($company->email)->attachData($pdf->output(), "address.pdf");

            });
        } catch (\Exception $e) {
            // return response showing failed emails
            //API URL
            $url = 'https://hooks.slack.com/services/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';

//create a new cURL resource
            $ch = curl_init($url);
            $data = "Hey <!channel>, A job has failed :shit::shit::shit:, please find the detail below and manually send email to those company";
            $data = $data . "\n";
            $data = $data . ">User ID: " . $history->user->unique_id;
            $data = $data . "\n";
            $data = $data . ">Email: " . $history->email;
            $data = $data . "\n";
            $data = $data . ">Address: " . $history->address;
            $data = $data . "\n";
            $data = $data . ">Phone: " . $history->tel;
            $data = $data . "\n";
            $data = $data . "Notification should go to: " . $company->legal_name . " with the email of: " . $company->email;
            $data = $data . "\n";
            $data = $data . "```Error " . $e->getMessage() . "```";
            $payload = json_encode(array("text" => $data));

//attach encoded JSON string to the POST fields
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

//set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

//return response instead of outputting
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute the POST request
            $result = curl_exec($ch);

//close cURL resource
            curl_close($ch);

        }




    }
}
