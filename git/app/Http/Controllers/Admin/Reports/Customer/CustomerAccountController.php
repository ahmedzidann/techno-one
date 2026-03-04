<?php

namespace App\Http\Controllers\Admin\Reports\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ClientCoupounBalance;
use Yajra\DataTables\Facades\DataTables;

class CustomerAccountController extends Controller
{
          public function __construct()
    {
        $this->middleware('permission:عرض الادوار,admin')->only('index');
        $this->middleware('permission:تعديل الادوار,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء الادوار,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف الادوار,admin')->only('destroy');
    }

    public function index(Request $request)
{
    if ($request->ajax()) {
        $client_id = intval($request->client_id);

        // 1️⃣ جلب كوبونات العميل
        $coupons = ClientCoupounBalance::CustomerBalanceQuery($client_id)->get();

        // 2️⃣ جلب الرصيد السابق
        $client = DB::table('clients')->where('id', $client_id)->first();
        $previous_balance = $client ? floatval($client->previous_indebtedness ?? 0) : 0;

        // 3️⃣ حساب الرصيد التراكمي من الرصيد السابق
        $currentBalance = $previous_balance;
        $rows = $coupons->map(function($row) use (&$currentBalance) {
            $row->credit = floatval($row->credit);
            $row->debt   = floatval($row->debt);

            // تحديث الرصيد التراكمي
            $currentBalance += ($row->credit - $row->debt); // increase - decrease
            $row->balance = $currentBalance;

            return $row;
        });

        // 4️⃣ ارجع DataTables مع previous_balance للعرض
        return DataTables::of($rows)
            ->with('previous', $previous_balance)
            ->make(true);
    }

    return view('Admin.reports.accountStatement.clientAccountStatement.customer');
}



public function customers_balances(Request $request)
{
    if ($request->ajax()) {

       $query = ClientCoupounBalance::AllCustomersBalanceQuery();

        return DataTables::of($query)

            ->filter(function ($query) use ($request) {

                if ($request->search['value']) {

                    $search = $request->search['value'];

                      $query->where('c.name','like',"%{$search}%");
                      $query->orwhere('c.code','like',"%{$search}%");
                }
            })

            ->addColumn('final_balance', function ($row) {

                return $row->previous_balance
                    + $row->increase
                    - $row->decrease;
            })

            ->make(true);
    }

 return view('Admin.reports.accountStatement.clientAccountStatement.all_customers_balance');
}



}
