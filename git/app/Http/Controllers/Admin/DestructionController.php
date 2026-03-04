<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destruction;
use App\Models\DestructionDetails;
use App\Models\ProductionMaterial;
use App\Models\Productive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DestructionController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Destruction::query()->with(['storage']);
            return DataTables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';

                    return '
                            <button ' . $edit . '   class="editBtn-p  btn rounded-pill btn-primary waves-effect waves-light"
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
                    return "<button data-id='$row->id' class='btn btn-outline-dark showDetails'>عرض تفاصيل الاهلاك</button>";
                })

                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);

        }

        return view('Admin.CRUDS.destruction.index');
    }

    public function create()
    {
        $model = DB::table('destruction')->latest('id')->select('id')->first();
        if ($model) {
            $count = $model->id;
        } else {
            $count = 0;
        }

        return view('Admin.CRUDS.destruction.create', compact('count'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'storage_id' => 'required|exists:storages,id',
            'destruction_date' => 'required|date',

        ]);

        $datails = $request->validate([
            'productive_id' => 'required|array',
            'productive_id.*' => 'required',
            'amount' => 'required|array',
            'amount.*' => 'required',
            // 'type' => 'required|array',
            // 'type.*' => 'required',
            'batch_number' => 'required|array',
            'batch_number.*' => 'required',
            'productive_sale_price' => 'required|array',
            'productive_sale_price.*' => 'required',
            'productive_buy_price' => 'required|array',
            'productive_buy_price.*' => 'required',
        ]);

        if (count($request->amount) != count($request->productive_id)) {
            return response()->json(
                [
                    'code' => 421,
                    'message' => 'المنتج مطلوب',
                ]);
        }

        $model = DB::table('destruction')->latest('id')->select('id')->first();
        if ($model) {
            $count = $model->id;
        } else {
            $count = 0;
        }

        $data['publisher'] = auth('admin')->user()->id;
        $data['date'] = date('Y-m-d');
        $data['month'] = date('m');
        $data['year'] = date('Y');
        $data['destruction_number'] = $count + 1;

        $destruction = Destruction::create($data);

        $sql = [];

        if ($request->productive_id) {
            for ($i = 0; $i < count($request->productive_id); $i++) {

                $details = [];
                $productive = Productive::findOrFail($request->productive_id[$i]);

                $all_pieces = $request->amount[$i] * $productive->num_pieces_in_package;
                if ($request->type == 'department') {
                    $all_pieces = $request->amount[$i];
                }

                $details = [
                    'storage_id' => $destruction->storage_id,
                    'destruction_id' => $destruction->id,
                    'productive_id' => $request->productive_id[$i],
                    'productive_code' => $productive->code,
                    'amount' => $request->amount[$i],
                    'type' => 'wholesale',
                    'batch_number' => $request->batch_number[$i],
                    'productive_sale_price' => $request->productive_sale_price[$i],
                    'productive_buy_price' => $request->productive_buy_price[$i],
//                    'total'=>$request->price[$i]*$request->amount[$i],
                    // 'productive_type'=>$productive->product_type,
                    'all_pieces' => $all_pieces,
                    'date' => date('Y-m-d'),
                    'year' => date('Y'),
                    'month' => date('m'),
                    'publisher' => auth('admin')->user()->id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),

                ];

                array_push($sql, $details);
            }
            DB::table('destruction_details')->insert($sql);

        }

//        foreach (DestructionDetails::where('destruction_id',$destruction->id)->get() as $pivot){
//            $mainProductive=Productive::find($pivot->productive_id);
//            $itemInstallation=ItemInstallation::where('productive_id',$pivot->productive_id)->first();
//
//            foreach(ItemInstallationDetails::where('item_installation_id',$itemInstallation->id)->get() as $rowDetails ){
//                ProductionMaterial::create([
//                    'process'=>'destruction',
//                    'process_id'=>$destruction->id,
//                    'main_productive_id'=>$pivot->productive_id,
//                    'main_amount'=>$pivot->amount,
//                    'productive_id'=>$rowDetails->productive_id,
//                    'amount'=>$rowDetails->amount,
//                    'all_amount'=>$pivot->amount*$rowDetails->amount,
//                    'date'=>date('Y-m-d'),
//                    'publisher'=>auth('admin')->user()->id,
//                    'month'=>date('m'),
//                    'year'=>date('Y'),
//                    'created_at'=>date('Y-m-d H:i:s'),
//                    'updated_at'=>date('Y-m-d H:i:s'),
//
//
//                ]);
//            }
//        }

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function edit($id)
    {
        $row = Destruction::find($id);

        return view('Admin.CRUDS.destruction.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'storage_id' => 'required|exists:storages,id',
            'destruction_date' => 'required|date',

        ]);

        $datails = $request->validate([
            'productive_id' => 'required|array',
            'productive_id.*' => 'required',
            'amount' => 'required|array',
            'amount.*' => 'required',
            // 'type' => 'required|array',
            // 'type.*' => 'required',
            'batch_number' => 'required|array',
            'batch_number.*' => 'required',
            'productive_sale_price' => 'required|array',
            'productive_sale_price.*' => 'required',
            'productive_buy_price' => 'required|array',
            'productive_buy_price.*' => 'required',
        ]);

        if (count($request->amount) != count($request->productive_id)) {
            return response()->json(
                [
                    'code' => 421,
                    'message' => 'المنتج مطلوب',
                ]);
        }

        $destruction = Destruction::findOrFail($id);
        $destruction->update($data);

        DestructionDetails::where('destruction_id', $id)->delete();

        $sql = [];

        if ($request->productive_id) {
            for ($i = 0; $i < count($request->productive_id); $i++) {

                $details = [];
                $productive = Productive::findOrFail($request->productive_id[$i]);
                $all_pieces = $request->amount[$i] * $productive->num_pieces_in_package;
                if ($request->type == 'department') {
                    $all_pieces = $request->amount[$i];
                }

                $details = [
                    'storage_id' => $destruction->storage_id,
                    'destruction_id' => $destruction->id,
                    'productive_id' => $request->productive_id[$i],
                    'productive_code' => $productive->code,
                    'amount' => $request->amount[$i],
                    'type' => 'wholesale',
                    'batch_number' => $request->batch_number[$i],
                    'productive_sale_price' => $request->productive_sale_price[$i],
                    'productive_buy_price' => $request->productive_buy_price[$i],
//                    'total'=>$request->price[$i]*$request->amount[$i],
                    // 'productive_type' => $productive->product_type,
                    'all_pieces' => $all_pieces,
                    'date' => $destruction->date,
                    'year' => $destruction->year,
                    'month' => $destruction->month,
                    'publisher' => $destruction->publisher,
                    'created_at' => $destruction->created_at,
                    'updated_at' => date('Y-m-d H:i:s'),

                ];

                array_push($sql, $details);
            }
            DB::table('destruction_details')->insert($sql);

//            ProductionMaterial::where('process_id',$id)->where('process','destruction')->delete();
//
//
//                    foreach (DestructionDetails::where('destruction_id',$destruction->id)->get() as $pivot){
//            $mainProductive=Productive::find($pivot->productive_id);
//            $itemInstallation=ItemInstallation::where('productive_id',$pivot->productive_id)->first();
//
//            foreach(ItemInstallationDetails::where('item_installation_id',$itemInstallation->id)->get() as $rowDetails ){
//                ProductionMaterial::create([
//                    'process'=>'destruction',
//                    'process_id'=>$destruction->id,
//                    'main_productive_id'=>$pivot->productive_id,
//                    'main_amount'=>$pivot->amount,
//                    'productive_id'=>$rowDetails->productive_id,
//                    'amount'=>$rowDetails->amount,
//                    'all_amount'=>$pivot->amount*$rowDetails->amount,
//                    'date'=>$destruction->publisher,
//                    'publisher'=>$destruction->publisher,
//                    'month'=>$destruction->month,
//                    'year'=>$destruction->year,
//                    'created_at'=>$destruction->created_at,
//                    'updated_at'=>date('Y-m-d H:i:s'),
//
//
//                ]);
//            }
//
//
//
//        }

        }

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function destroy($id)
    {

        $row = Destruction::find($id);

        ProductionMaterial::where('process', 'destruction')->where('process_id', $row->id)->delete();

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun

    public function getDestructionDetails($id)
    {
        $destruction = Destruction::findOrFail($id);
        $rows = DestructionDetails::where('destruction_id', $id)->with(['productive'])->get();
        return view('Admin.CRUDS.destruction.parts.destructionDetails', compact('rows'));
    }

    public function makeRowDetailsForDestructionDetails()
    {
        $id = rand(2, 999999999999999);
        $html = view('Admin.CRUDS.destruction.parts.details', compact('id'))->render();

        return response()->json(['status' => true, 'html' => $html, 'id' => $id]);
    }
    public function getDestructionPrice(Request $request)
    {

        $productive = Productive::findOrFail($request->productive_id);
        $price = $productive->one_buy_price;
        if ($request->type == 'wholesale') {
            $price = $productive->packet_buy_price;
        }

        return response()->json(['status' => true, 'price' => $price]);
    }

}
