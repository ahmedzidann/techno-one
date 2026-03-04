<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ItemInstallation;
use App\Models\ItemInstallationDetails;
use App\Models\Productive;
use App\Services\ProductBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ItemInstallationController extends Controller
{
    //
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = ItemInstallation::query()->with(['productive']);
            return DataTables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';

                    return '
                            <button ' . $edit . '   class="editBtn btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </span>
                            </button>
                            <button ' . $delete . '  class="btn rounded-pill btn-danger waves-effect waves-light delete"
                                    data-id="' . $row->id . '">
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </span>
                            </button>
                       ';
                })

                ->addColumn('details', function ($row) {

                    return 'التفاصيل';
                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.itemInstallations.index');
    }

    public function create()
    {
        $mainProductive = Productive::where('product_type', 'tam')->get();
        $subProductive = Productive::where('product_type', 'kham')->get();
        return view('Admin.CRUDS.itemInstallations.parts.create', compact('mainProductive', 'subProductive'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'main_productive_id' => 'required|exists:productive,id',
            'install_date' => 'required|date',
            'productive_id' => 'required|array',
            'productive_id.*' => 'required',
            'amount' => 'required|array',
            'amount.*' => 'required',

        ]);

        $row = ItemInstallation::create([
            'productive_id' => $request->main_productive_id,
            'install_date' => $request->install_date,
            'date' => date('Y-m-d'),
            'year' => date('Y'),
            'month' => date('m'),
            'publisher' => auth('admin')->user()->id,
        ]);

        $sql = [];

        if ($request->productive_id) {
            for ($i = 0; $i < count($request->productive_id); $i++) {

                $details = [];
                $productive = Productive::findOrFail($request->productive_id[$i]);

                $details = [

                    'item_installation_id' => $row->id,
                    'main_productive_id' => $request->main_productive_id,
                    'productive_id' => $request->productive_id[$i],
                    'productive_code' => $productive->code,
                    'amount' => $request->amount[$i],
                    'date' => date('Y-m-d'),
                    'year' => date('Y'),
                    'month' => date('m'),
                    'publisher' => auth('admin')->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),

                ];

                array_push($sql, $details);
            }
            DB::table('item_installation_details')->insert($sql);
        }

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    public function edit($id)
    {

        $row = ItemInstallation::with(['productive'])->findOrFail($id);

        return view('Admin.CRUDS.itemInstallations.parts.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'install_date' => 'required|date',
            'productive_id' => 'required|array',
            'productive_id.*' => 'required',
            'amount' => 'required|array',
            'amount.*' => 'required',

        ]);

        $row = ItemInstallation::find($id);
        $row->update([
            'install_date' => $request->install_date,
        ]);

        ItemInstallationDetails::where('main_productive_id', $row->productive_id)->delete();

        $sql = [];

        if ($request->productive_id) {
            for ($i = 0; $i < count($request->productive_id); $i++) {

                $details = [];
                $productive = Productive::findOrFail($request->productive_id[$i]);

                $details = [

                    'item_installation_id' => $row->id,
                    'main_productive_id' => $row->productive_id,
                    'productive_id' => $request->productive_id[$i],
                    'productive_code' => $productive->code,
                    'amount' => $request->amount[$i],
                    'date' => date('Y-m-d'),
                    'year' => date('Y'),
                    'month' => date('m'),
                    'publisher' => auth('admin')->user()->id,

                ];

                array_push($sql, $details);
            }
            DB::table('item_installation_details')->insert($sql);
        }

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    public function destroy($id)
    {

        $row = ItemInstallation::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    } //end fun

    public function getSubProductive()
    {
        $subProductive = Productive::where('product_type', 'kham')->get();
        return view('Admin.CRUDS.itemInstallations.parts.subProductive', compact('subProductive'));
    }

    public function makeRowDetailsForItemInstallations()
    {
        $id = rand(2, 999999999999999);
        $html = view('Admin.CRUDS.itemInstallations.parts.details', compact('id'))->render();

        return response()->json(['status' => true, 'html' => $html, 'id' => $id]);
    }

    public function getProductiveDetails(Request $request)
    {
        $client = Client::with('subscription')->findOrFail($request->clientId);
        $batches = DB::table('purchases_details')
            ->select('batch_number', DB::raw('MAX(created_at) as latest_created_at'))
            ->where('productive_id', $request->productId)
            ->groupBy('batch_number')
            ->orderBy('latest_created_at', 'desc')
            ->get();

        $productive = Productive::findOrFail($request->productId);
        $productive_buy_price = $productive->audience_price;
        $latestPurchaseForProductive = DB::table('purchases_details')->where('productive_id', $request->productId)->latest()->first();
        $productBalance = (new ProductBalance($request->productId))->calculateBalance();
        return response()->json([
            'status' => true,
            'productive' => $productive,
            'product_balance' => $productBalance,
            'code' => $productive->code,
            'unit' => $productive->unit->title ?? '',
            'name' => $productive->name,
            'productive_id' => $productive->id,
            'productive_buy_price' => $productive_buy_price,
            'batches' => $batches,
            'active_likely_discount' => $latestPurchaseForProductive->active_likely_discount ?? 0,
            'client_discount' => $client->subscription?->discount ?? 0,
        ]);
    }

    public function getProductiveDetailsForPurchase($id)
    {
        $productive = Productive::with(['batches' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        
        return response()->json([
            'status' => true,
            'productive' => $productive,
            'code' => $productive->code,
            'unit' => $productive->unit->title ?? '',
            'name' => $productive->name,
            'productive_id' => $productive->id,
            'productive_buy_price' => $productive->audience_price,
            'productive_sale_price' => $productive->audience_price,
        ]);
    }
    public function getProductiveTamDetails($id)
    {
        $productive = Productive::where('product_type', 'tam')->findOrFail($id);

        return response()->json(['status' => true, 'productive' => $productive, 'code' => $productive->code, 'unit' => $productive->unit->title ?? '', 'name' => $productive->name, 'productive_id' => $productive->id]);
    }

    public function getProductiveTypeKham(Request $request)
    {
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('productive') /*->where('product_type','kham')*/->select('id', 'name as text')
                ->where('name', 'LIKE', '%' . $term . '%')
                ->orderBy('name', 'asc')->simplePaginate(3);

            $morePages = true;
            $pagination_obj = json_encode($posts);
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);
        }
    }

    public function getProductiveTypeTam(Request $request)
    {
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('productive')->where('product_type', 'tam')->select('id', 'name as text')
                ->where('name', 'LIKE', '%' . $term . '%')
                ->orderBy('name', 'asc')->simplePaginate(3);

            $morePages = true;
            $pagination_obj = json_encode($posts);
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);
        }
    }

    public function getAllProductive(Request $request)
    {

        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('productive')->select('id', 'name as text')
                ->where('name', 'LIKE', '%' . $term . '%')
                ->orderBy('name', 'asc')->simplePaginate(3);

            $morePages = true;
            $pagination_obj = json_encode($posts);
            if (empty($posts->nextPageUrl())) {
                $morePages = false;
            }
            $results = array(
                "results" => $posts->items(),
                "pagination" => array(
                    "more" => $morePages,
                ),
            );

            return \Response::json($results);
        }
    }
}
