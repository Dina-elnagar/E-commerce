<?php

namespace App\Listeners;

use App\Events\ProductOutOfStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\OutOfStock;

class ProductOutOfStockNotification
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
     * @param  \App\Events\ProductOutOfStock  $event
     * @return void
     */
     public function handle(ProductOutOfStock $event)
     {

    Mail::to('admin@34ml.com')->send(new OutOfStock($event->product));

     }
}
