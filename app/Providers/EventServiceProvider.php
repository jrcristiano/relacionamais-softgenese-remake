<?php

namespace App\Providers;

use App\Events\TransferAtCashFlowCreated;
use App\Events\TransferAtCashFlowUpdated;
use App\Listeners\SaveTransferAtCashFlowListener;
use App\Listeners\UpdateTransferAtCashFlowListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        TransferAtCashFlowCreated::class => [
            SaveTransferAtCashFlowListener::class,
        ],
        TransferAtCashFlowUpdated::class => [
            UpdateTransferAtCashFlowListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
