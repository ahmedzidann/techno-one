<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SupplierAccount
{
    public static function SupplierBalance($supplier_id) {

            $purchases = DB::table('purchases')
                ->selectRaw('SUM(total - paid) as balance_difference')
                ->where('supplier_id', $supplier_id)
                ->first();

            $headBackPurchases = DB::table('head_back_purchases')
                ->where('supplier_id', $supplier_id)
                ->sum('paid');

            $vouchers = DB::table('supplier_vouchers')
                ->where('supplier_id', $supplier_id)
                ->sum('paid');


        return $purchases->balance_difference - $headBackPurchases - $vouchers;
    }
}
