<?php

namespace App\Listeners;

use App\Events\eventMessage;
use Illuminate\Broadcasting\PrivateChannel;

class ListenerMessage
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public $message;
    public function __construct( $message  )
    {
         $this->message =$message;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {

    }

    public function broadcastOn()
    {
    }

    public function broadcastAs()
	{
		return 'listen-message';
	}
}
