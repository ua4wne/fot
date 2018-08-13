<?php

namespace App\Handlers\Events;

use App\Events\AddEventLogs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventLog
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
     * @param  AddEventLogs  $event
     * @return void
     */
    public function handle(AddEventLogs $event)
    {
        //
    }
}
