<?php

namespace App\Listeners;

use App\Events\OrderShoppingCart;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailConfirmShoppingCart implements ShouldQueue
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
     * @param  OrderShoppingCart  $event
     * @return void
     */
    public function handle(OrderShoppingCart $event)
    {
        //
    }
}
