<?php

namespace App\Http\Controllers\Admin\Reports\AccountStatements;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierAccountStatmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
            ]);

            $rows = DB::query()
                ->fromSub(function ($query) use ($request) {
                    // Purchases Query
                    $query->from('purchases')
                        ->where('supplier_id', $request->supplier_id)
                        ->select(
                            DB::raw('purchases_date as date'),
                            DB::raw('total as total_price'),
                            'paid',
                            DB::raw("'purchases' as type")
                        )

                        // Head Back Purchases Query
                        ->unionAll(
                            DB::table('head_back_purchases')
                                ->where('supplier_id', $request->supplier_id)
                                ->select(
                                    DB::raw('purchases_date as date'),
                                    DB::raw('total as total_price'),
                                    'paid',
                                    DB::raw("'headBackPurchases' as type")
                                )
                        )

                        // Vouchers Query
                        ->unionAll(
                            DB::table('supplier_vouchers')
                                ->where('supplier_id', $request->supplier_id)
                                ->select(
                                    DB::raw('voucher_date as date'),
                                    DB::raw('paid as total_price'),
                                    DB::raw('0 as paid'),
                                    DB::raw("'voucher' as type")
                                )
                        );
                }, 'combined_data')
                ->orderBy('date', 'DESC')
                ->get();

            $supplier = Supplier::findOrFail($request->supplier_id);
            $html = view('Admin.reports.accountStatement.supplierAccountStatement.parts.table', compact('supplier', 'rows'))->render();
            return response()->json([
                'status' => true,
                'html' => $html,
            ]);
        }
        return view('Admin.reports.accountStatement.supplierAccountStatement.index');
    }
}
