<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Productive;
use App\Services\ProductBalance;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductLowBalanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Productive::when($request->product_id, fn($q) => $q->where('id', $request->product_id))->with(['unit', 'company', 'category'])->get()->filter(function ($product) use ($request) {
                $balance = (new ProductBalance($product->id, $request->storage_id))->calculateBalance();
                $product->remainder = $balance;
                return $balance <= $product->limit_for_request;
            });

            return DataTables::of($query)

                ->addColumn('balance', function ($product) {
                    return (new ProductBalance($product->id))->calculateBalance();
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.productive.low_balance_index');
    }
}
