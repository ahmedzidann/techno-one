<?php

namespace App\Services;

use App\Enum\PurchaseStatus;
use App\Models\PurchasesDetails;
use DateTime;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProductBalance
{
    /**
     * Constructor to inject product ID and optional filters.
     */
    public function __construct(
        public int $productId,
        public ?int $storageId = null,
        public ?DateTime  $startDate = null,
        public ?DateTime  $endDate = null
    ) {}

    /**
     * Calculate the product balance.
     */
    public function calculateBalance()
    {
        // Define a reusable query filter closure
        $applyFilters = function ($query) {
            return $query
                ->when($this->storageId, fn($q) => $q->where('storage_id', $this->storageId))
                ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
                ->when($this->endDate, fn($q) => $q->whereDate('created_at', '<=', $this->endDate));
        };

        // Query summaries
        $salesDetails = $applyFilters(DB::table('sales_details')->selectRaw('SUM(amount+ bouns) as total_amount')->where('is_prepared', 1)->where('productive_id', $this->productId))->first();
        $purchasesDetails = $applyFilters(PurchasesDetails::whereHas('purchases', fn($q) => $q->where('status', PurchaseStatus::COMPLETED))->selectRaw('SUM(amount+ bouns) as total_amount')->where('productive_id', $this->productId))->first();
        $headBackSalesDetails = $applyFilters(DB::table('head_back_sales_details')->selectRaw('SUM(amount+ bouns) as total_amount')->where('productive_id', $this->productId))->first();
        $headBackPurchasesDetails = $applyFilters(DB::table('head_back_purchases_details')->selectRaw('SUM(amount+ bouns) as total_amount')->where('productive_id', $this->productId))->first();

        // Individual sums
        $destructionDetails = $applyFilters(DB::table('destruction_details')->where('productive_id', $this->productId))->sum('amount');
        $incrementalAdjustment = $applyFilters(DB::table('product_adjustments')->where('type', 1)->where('product_id', $this->productId))->sum('amount');
        $deficitAdjustment = $applyFilters(DB::table('product_adjustments')->where('type', 2)->where('product_id', $this->productId))->sum('amount');

        // Safely handle null values for totals
        $salesTotal = ($salesDetails->total_amount ?? 0) + ($salesDetails->total_bonus ?? 0);
        $purchasesTotal = ($purchasesDetails->total_amount ?? 0) + ($purchasesDetails->total_bonus ?? 0);
        $headBackSalesTotal = ($headBackSalesDetails->total_amount ?? 0) + ($headBackSalesDetails->total_bonus ?? 0);
        $headBackPurchasesTotal = ($headBackPurchasesDetails->total_amount ?? 0) + ($headBackPurchasesDetails->total_bonus ?? 0);

        // Calculate balance
        return ($purchasesTotal + $headBackSalesTotal) - ($salesTotal + $headBackPurchasesTotal + $destructionDetails) + $incrementalAdjustment - $deficitAdjustment;
    }
    public function calculateActiveLikelyDiscount(
        int|float $newAmount,
        int|float $newGrossPrice,
        int|float $newNetPrice,
        int|float|null $oldNetPrice,
        int|float $likelyDiscount,
        int|float|null $bouns
    ): float {
        if (!$oldNetPrice) {
            return \number_format(
                $this->firstPrice($bouns, $newAmount, $newNetPrice, $newGrossPrice), 2);
        }
        // Guard clause for zero gross price
        if ($newGrossPrice === 0) {
            throw new InvalidArgumentException('Gross price cannot be zero');
        }

        $oldAmount = $this->calculateBalance();

        // Calculate weighted average price
        $averagePrice = $this->calculateWeightedAveragePrice(
            $oldAmount,
            $oldNetPrice,
            $newAmount,
            $newNetPrice
        );

        // Calculate price difference percentage
        $priceDifference = $newGrossPrice - $averagePrice;

        return \number_format($this->calculateDiscountPercentage($priceDifference, $newGrossPrice), 2);
    }

    /**
     * Calculate weighted average price based on old and new quantities and prices
     */
    private function calculateWeightedAveragePrice(
        int|float $oldAmount,
        int|float $oldNetPrice,
        int|float $newAmount,
        int|float $newNetPrice
    ): float {
        $totalAmount = $oldAmount + $newAmount;

        // Guard clause for zero total amount
        if ($totalAmount === 0) {
            throw new InvalidArgumentException('Total amount cannot be zero');
        }

        $totalPrice = ($oldNetPrice * $oldAmount) + ($newNetPrice * $newAmount);

        return $totalPrice / $totalAmount;
    }

    /**
     * Calculate discount percentage based on price difference
     */
    private function calculateDiscountPercentage(float $priceDifference, int $newGrossPrice): float
    {
        if ($priceDifference > 0) {
            return ($priceDifference / $newGrossPrice) * 100;
        }

        return (abs($priceDifference) / $newGrossPrice) * 100 + 1;
    }


    public function firstPrice($bouns, $newAmount, $newNetPrice, $newGrossPrice)
    {
        $itemPrice = $newNetPrice / $newAmount + $bouns;
        $priceOfBouns = $itemPrice * $bouns;
        $totalNetPrice = $newNetPrice - $priceOfBouns;
        $priceDifference = $newGrossPrice - $totalNetPrice;

        if ($priceDifference > 0) {
            return ($priceDifference / $newGrossPrice) * 100;
        }

        return (abs($priceDifference) / $newGrossPrice) * 100 + 1;
    }
}
