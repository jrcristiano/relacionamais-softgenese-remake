<?php

namespace App\Providers;

use App\Events\Awards\UpdateAwardedValueAfterRemoveAwarded;
use App\Events\PaymentManuals\PaymentManualAtCashFlowCreated;
use App\Events\PaymentManuals\PaymentManualAtCashFlowUpdated;
use App\Events\Transfers\TransferAtCashFlowSaved;
use App\Listeners\Awards\AwardUpdateAwardedValueAfterRemoveAwardedListener;
use App\Listeners\PaymentManuals\PaymentManualAtCashFlowListener;
use App\Listeners\PaymentManuals\UpdatePaymentManualAtCashFlowListener;
use App\Listeners\Transfers\SaveTransferAtCashFlowListener;
use App\Listeners\Transfers\UpdateTransferAtCashFlowListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        TransferAtCashFlowSaved::class => [
            SaveTransferAtCashFlowListener::class,
        ],
        TransferAtCashFlowSaved::class => [
            UpdateTransferAtCashFlowListener::class,
        ],
        PaymentManualAtCashFlowCreated::class => [
            PaymentManualAtCashFlowListener::class,
        ],
        PaymentManualAtCashFlowUpdated::class => [
            UpdatePaymentManualAtCashFlowListener::class,
        ],
        UpdateAwardedValueAfterRemoveAwarded::class => [
            AwardUpdateAwardedValueAfterRemoveAwardedListener::class,
        ]
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
