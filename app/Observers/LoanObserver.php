<?php

namespace App\Observers;

use App\Models\Loan;
use App\Models\Items;
use App\Models\LoanItem;

class LoanObserver
{
    public function deleted(Loan $loan)
    {
        $loan->loadMissing('loanItems');

        foreach ($loan->loanItems as $loanItem) {
            if ($item = Items::find($loanItem->item_id)) {
                $item->increment('stock', $loanItem->quantity);
            }
        }
    }
}
