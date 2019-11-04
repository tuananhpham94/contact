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
        //API URL
        $url = 'https://hooks.slack.com/services/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';

//create a new cURL resource
        $ch = curl_init($url);

//setup request to send json via POST
        $data = "User ". $user->name . " - Unique ID: `". $user->unique_id ."` has changed his / her contact detail to: ";
        $data = $data . "\n";
        $data = $data . ">Email: " . $this->history->email;
        $data = $data . "\n";
        $data = $data . ">Address: " . $this->history->address;
        $data = $data . "\n";
        $data = $data . ">Phone: " . $this->history->tel;
        $data = $data . "\n";
        $data = $data . "Incoming notification to " . implode (", ", $companies);
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
