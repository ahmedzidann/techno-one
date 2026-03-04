<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ClientCoupounBalance
{
    /**
     * جلب كوبونات العميل
     */
    public static function CustomerBalanceQuery($client_id)
    {
        $client_id = intval($client_id);

        $query = DB::table('coupons_converts as cc')
            ->leftJoin('clients as c1', 'c1.id', '=', 'cc.from_user_id')
            ->leftJoin('clients as c2', 'c2.id', '=', 'cc.to_user_id')
            ->where(function($q) use ($client_id) {
                $q->where('cc.from_user_id', $client_id)
                  ->orWhere('cc.to_user_id', $client_id);
            })
             ->where('cc.status', 'approved') 
            ->select([
                'cc.id',
                'cc.amount',
                'cc.notes',
                 'cc.invoice_number',
                'cc.converted_at as date',
                DB::raw("CASE WHEN cc.from_user_id = $client_id THEN 'decrease' ELSE 'increase' END as type_label"),
                DB::raw("CASE WHEN cc.from_user_id = $client_id THEN cc.amount ELSE 0 END as debt"),
                DB::raw("CASE WHEN cc.from_user_id = $client_id THEN 0 ELSE cc.amount END as credit"),
                DB::raw("CASE WHEN (CASE WHEN cc.from_user_id = $client_id THEN cc.to_user_id ELSE cc.from_user_id END) = 0 THEN 'techno-one' ELSE COALESCE(c1.name,c2.name) END as other_user_name")
            ])
            ->orderBy('cc.converted_at', 'asc');

        return $query;
    }

    /**
     * جلب أرصدة كل العملاء
     */
   public static function AllCustomersBalanceQuery()
{
    return DB::table('clients as c')
        ->leftJoin('coupons_converts as cc', function ($join) {
            $join->on(function ($q) {
                $q->on('c.id', '=', 'cc.from_user_id')
                  ->orOn('c.id', '=', 'cc.to_user_id');
            })
            ->where('cc.status', '=', 'approved');
        })
        ->select([
            'c.id',
            'c.name',
            'c.code',
            'c.phone',
            DB::raw('COALESCE(c.previous_indebtedness,0) as previous_balance'),

            DB::raw("
                COALESCE(SUM(
                    CASE WHEN c.id = cc.from_user_id THEN cc.amount ELSE 0 END
                ),0) as decrease
            "),

            DB::raw("
                COALESCE(SUM(
                    CASE WHEN c.id = cc.to_user_id THEN cc.amount ELSE 0 END
                ),0) as increase
            ")
        ])
        ->groupBy(
            'c.id',
            'c.name',
            'c.code',
            'c.phone',
            'c.previous_indebtedness'
        );
}


}
