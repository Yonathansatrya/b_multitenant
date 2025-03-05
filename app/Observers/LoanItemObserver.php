<?php

namespace App\Observers;

use App\Models\LoanItem;
use App\Models\Items;

class LoanItemObserver
{
    public function created(LoanItem $loanItem)
    {
        $item = Items::find($loanItem->item_id);

        if ($item && $item->stock >= $loanItem->quantity) {
            $item->decrement('stock', $loanItem->quantity);
        }
    }

    public function updated(LoanItem $loanItem)
    {
        $item = Items::find($loanItem->item_id);

        if ($item) {
            $originalQuantity = $loanItem->getOriginal('quantity');
            $newQuantity = $loanItem->quantity;

            if ($newQuantity > $originalQuantity) {
                $difference = $newQuantity - $originalQuantity;
                if ($item->stock >= $difference) {
                    $item->decrement('stock', $difference);
                }
            } elseif ($newQuantity < $originalQuantity) {
                $difference = $originalQuantity - $newQuantity;
                $item->increment('stock', $difference);
            }
        }
    }
}
