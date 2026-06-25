<?php

namespace App\Observers;

use App\Models\Purchase_Detail;
use App\Models\Purchase;
use App\Models\Products;

class PurchaseDetailObserver
{
    /**
     * Create Purchase Detail
     * Increase Product Stock
     * Update Purchase Total
     */
    public function created(Purchase_Detail $purchaseDetail): void
    {
        // Increase Stock
        $product = Products::find($purchaseDetail->product_id);
        if ($product) {
            $product->increment(
                'quantity',
                $purchaseDetail->quantity
            );
        }

        $this->adjustPurchaseTotal(
            $purchaseDetail->purchase_id,
            -$purchaseDetail->subtotal
        );
    }

    /**
     * Update Purchase Detail
     */
    public function updated(Purchase_Detail $purchaseDetail): void
    {
        $product = Products::find($purchaseDetail->product_id);
        if ($product) {
            $oldQty = $purchaseDetail->getOriginal('quantity');
            $newQty = $purchaseDetail->quantity;
            $difference = $newQty - $oldQty;
            if ($difference > 0) {
                $product->increment(
                    'quantity',
                    $difference
                );

            } elseif ($difference < 0) {

                $product->decrement(
                    'quantity',
                    abs($difference)
                );
            }
        }

        $oldSubtotal = $purchaseDetail->getOriginal('quantity') *$purchaseDetail->getOriginal('unit_price');

        $newSubtotal = $purchaseDetail->quantity *
        $purchaseDetail->unit_price;

        $difference = $newSubtotal - $oldSubtotal;

        $this->adjustPurchaseTotal(
            $purchaseDetail->purchase_id,
            -$difference
        );
    }

    /**
     * Delete Purchase Detail
     */
    public function deleted(Purchase_Detail $purchaseDetail): void
    {
        $product = Products::find(
            $purchaseDetail->product_id
        );

        if ($product) {

            $product->decrement(
                'quantity',
                $purchaseDetail->quantity
            );
        }

        $this->adjustPurchaseTotal(
            $purchaseDetail->purchase_id,
            $purchaseDetail->subtotal
        );
    }

    /**
     * Restore Purchase Detail
     */
    public function restored(Purchase_Detail $purchaseDetail): void
    {
        $product = Products::find(
            $purchaseDetail->product_id
        );

        if ($product) {

            $product->increment(
                'quantity',
                $purchaseDetail->quantity
            );
        }

        $this->adjustPurchaseTotal(
            $purchaseDetail->purchase_id,
            -$purchaseDetail->subtotal
        );
    }

    /**
     * Force Delete
     */
    public function forceDeleted(Purchase_Detail $purchaseDetail): void
    {
        $product = Products::find(
            $purchaseDetail->product_id
        );

        if ($product) {

            $product->decrement(
                'quantity',
                $purchaseDetail->quantity
            );
        }

        $this->adjustPurchaseTotal(
            $purchaseDetail->purchase_id,
            $purchaseDetail->subtotal
        );
    }

    /**
     * Adjust Purchase Total Amount
     */
    private function adjustPurchaseTotal(
        int $purchaseId,
        float $amount
    ): void
    {
        $purchase = Purchase::find($purchaseId);

        if (!$purchase) {
            return;
        }

        $purchase->total_amount = max(
            0,
            $purchase->total_amount + $amount
        );

        $purchase->save();
    }
}