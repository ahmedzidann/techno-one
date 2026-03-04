<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\DB;

class CustomerAccount
{
    public static function CustomerBalance($client_id) {
        $client = Client::where('id', $client_id)->first();

        $sales = DB::table('sales')
            ->where('status', 'complete')
            ->where('client_id', $client_id)
            ->sum('total');

        $headBackSales = DB::table('head_back_sales')
            ->where('client_id', $client_id)
            ->sum('total');

        $esalat = DB::table('esalats')
            ->where('client_id', $client_id)
            ->sum('paid');

        $clientAdjustments = DB::table('client_adjustments')
            ->where('client_id', $client_id)
            ->select(
                DB::raw('SUM(CASE WHEN type = 1 THEN value ELSE 0 END) as total_debt'),
                DB::raw('SUM(CASE WHEN type = 2 THEN value ELSE 0 END) as total_credit')
            )
            ->first();

            $totalDebt = $sales + ($clientAdjustments->total_debt ?? 0) + $client->previous_indebtedness;
        $totalCredit = $headBackSales + $esalat + ($clientAdjustments->total_credit ?? 0);

        return $totalDebt - $totalCredit;
    }
}
