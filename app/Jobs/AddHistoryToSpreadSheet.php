<?php

namespace App\Jobs;

use App\Company;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddHistoryToSpreadSheet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $history;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($history)
    {
        $this->history = $history;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.__DIR__.'/client_secret.json');
        $client = new \Google_Client();
        $client->useApplicationDefaultCredentials();

        $client->setApplicationName("Something to do with my representatives");

        $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);
        if ($client->isAccessTokenExpired()) {
            $client->refreshTokenWithAssertion();
        }
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = '1E7gtV-_p2I0O9-qEFsSrXf6TKv5UIj5H4S6npnk_oNY';  // Zones International - Consumer Lead Tracker - DO NOT EDIT

        $sheetInfo = $service->spreadsheets->get($spreadsheetId)->getProperties();
        $options = array('valueInputOption' => 'RAW');
        $user = User::find($this->history->user_id);
        $notifications = Notification::where('history_id', $this->history->id)->get();
        $companies = [];
        foreach($notifications as $notification) {
            array_push($companies, Company::find($notification->company_id)->legal_name);
        }
        $v = [
            [
                Carbon::parse($this->history->created_at)->format("Y-m-d"), $user->name, $user->unique_id, $this->history->email, $this->history->address, $this->history->tel
            ]
        ];
        $body = new \Google_Service_Sheets_ValueRange(['values' => $v]);
        $rangeName = 'Sheet1!A2:E';
        $result = $service->spreadsheets_values->append($spreadsheetId, $rangeName, $body, $options);
        // send to slack
        $data = "User ". $user->name . " - Unique ID: `". $user->unique_id ."` has changed his / her contact detail to: " . "\n" . ">Email: " . $this->history->email
            . "\n" . ">Address: " . $this->history->address . "\n" . ">Phone: " . $this->history->tel . "\n" . "Incoming notification to " . implode (", ", $companies);
        slackIntegration($data);
    }
}
