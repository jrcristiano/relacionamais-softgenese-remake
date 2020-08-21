<?php

namespace App\Listeners\Awards;

use App\Events\Awards\UpdateAwardedValueAfterRemoveAwarded as Event;

class AwardUpdateAwardedValueAfterRemoveAwardedListener
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $award = $event->getAwardedRepo();
        $spreadsheet = $event->getSpreadsheetRepo();
        $id = $event->getId();

        $query = $spreadsheet->find($id);
        $spreadsheetValue = toMoney($query['spreadsheet_value']);
        $spreadsheetAwardId = $query['spreadsheet_award_id'];

        $query = $award->find($query['spreadsheet_award_id']);
        $awardedValue = toMoney($query['awarded_value']) / 100;

        $total = $awardedValue - $spreadsheetValue;

        $award->update(['awarded_value' => $total], 'id', $spreadsheetAwardId);
    }
}
