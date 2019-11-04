<?php

namespace App\Listeners;

use App\Company;
use App\Events\NotificationIsCreated;
use App\UserHistory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationIsCreated  $event
     * @return void
     */
    public function handle(NotificationIsCreated $event)
    {
//        $data = array('name'=>"Virat Gandhi");
//        Mail::send('mail', $data, function($message) {
//            $message->to('abc@gmail.com', 'Tutorials Point')->subject
//            ('Laravel HTML Testing Mail');
//            $message->from('xyz@gmail.com','Virat Gandhi');
//        });



        Mail::raw("Checkout new: " . UserHistory::find($event->notification->history_id)->user->name, function ($message) use ($event) {

            $message->from('anhpt@traffic.net.nz', 'Anh');

            $message->to(Company::find($event->notification->company_id)->email);

        });
    }
}
