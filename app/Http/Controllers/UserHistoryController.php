<?php

namespace App\Http\Controllers;

use App\Events\NotificationIsCreated;
use App\Jobs\AddHistoryToSpreadSheet;
use App\Jobs\SendEmailToCompany;
use App\Notification;
use App\User;
use App\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UserHistory $history)
    {
        $allHistory = $history->where('user_id', $request->user()->id)->get();
        foreach($allHistory as $history) {
            $notifications = Notification::where('history_id', $history->id)->get();
            foreach($notifications as $notification) {
                $notification['label'] = $notification->company->legal_name;
                $notification['value'] = $notification->company->id;
            }
            $history['selectedCompanies'] = $notifications;
        }
        $user = $request->user();
        return response()->json([
            'allHistory' => $allHistory,
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info("Request fired");

//        return DB::table('user_history')->latest('created_date')->first();
//        Take newest record with address email and tel
//    => compared them with newer record from request below, then fire out some zap to banks // probably events / queues combo to send email to banks at this stage
        $user = User::find($request->user()->id);
        $result = $user->updateHistory($request);
        if(!$result){
            return response()->json([
                'error' => true,
                'message' => "Email has to be unique"
            ]);
        }

        is_null($request->address) ? $address = "" : $address = $request->address;
        is_null($request->email) ? $email = "" : $email = $request->email;
        is_null($request->tel) ? $tel = "" : $tel = $request->tel;
        // 1 record of User History is created
        $history = $request->user()->userHistory()->create([
            'user_id' => $request->user()->id,
            'address' => $address,
            'email' => $email,
            'tel' => $tel,

        ]);
        // append this with the array of selected companies to fire back to react
        $history['selectedCompanies'] = $request->selectedCompanies;

        $notifications = [];
        // has to implement something in case no company is selected
        if($request->selectedCompanies) {
            foreach($request->selectedCompanies as $company) {
                $notifications = Notification::create([
                    'history_id' => $history->id,
                    'company_id' => $company['value']
                ]);
                SendEmailToCompany::dispatch($notifications)->delay(5);
            }
        }
        // work out a way to catch the failed jobs

        AddHistoryToSpreadSheet::dispatch($history)->delay(5);

        Log::info("Request ended");
        return response()->json([
            'history' => $history,
            'companies' => $request->selectedCompanies
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
