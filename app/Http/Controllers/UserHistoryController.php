<?php

namespace App\Http\Controllers;

use App\User;
use App\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $allHistory = $history->where('user_id', Auth::user()->id)->get();
        $user = Auth::user();
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
//        return DB::table('user_history')->latest('created_date')->first();
//        Take newest record with address email and tel
//    => compared them with newer record from request below, then fire out some zap to banks
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
        $history = $request->user()->userHistory()->create([
            'user_id' => $request->user()->id,
            'address' => $address,
            'email' => $email,
            'tel' => $tel,

        ]);
        return response()->json([
            'history' => $history
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
