<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ItemInstallation;
use App\Models\ItemInstallationDetails;
use App\Models\Production;
use App\Models\ProductionDetails;
use App\Models\ProductionMaterial;
use App\Models\Productive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductionController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Production::query()->with(['storage']);
            return DataTables::of( $rows)
                ->addColumn('action', function ($row) {

                    $edit='';
                    $delete='';


                    return '
                            <button '.$edit.'   class="editBtn-p  btn rounded-pill btn-primary waves-effect waves-light"
                                    data-id="' . $row->id . '"
                            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-edit"></i>
                                </span>
                            </span>
                            </button>
                            <button '.$delete.'  class="btn rounded-pill btn-danger waves-effect waves-light delete"
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
                    return "<button data-id='$row->id' class='btn btn-outline-dark showDetails'>عرض تفاصيل الانتاج</button>";
                })



                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);


        }

        return view('Admin.CRUDS.productions.index');
    }


    public function create()
    {
        $model=DB::table('productions')->latest('id')->select('id')->first();
        if ($model)
            $count=$model->id;
        else
            $count=0;

        return view('Admin.CRUDS.productions.create',compact('count'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'storage_id' => 'required|exists:storages,id' ,
            'production_date'=>'required|date',

        ]);


        $datails = $request->validate([
            'productive_id'=>'required|array',
            'productive_id.*'=>'required',
            'amount'=>'required|array',
            'amount.*'=>'required',

        ]);

        if (count($request->amount) != count($request->productive_id) )
            return response()->json(
                [
                    'code' => 421,
                    'message' => 'المنتج مطلوب'
                ]);

        $data['publisher']=auth('admin')->user()->id;
        $data['date']=date('Y-m-d');
        $data['month']=date('m');
        $data['year']=date('Y');

        $production= Production::create($data);



        $sql=[];

        if ($request->productive_id ) {
            for ($i = 0; $i < count($request->productive_id); $i++) {

                $details = [];
                $productive = Productive::findOrFail($request->productive_id[$i]);

                $details = [
                    'production_id' => $production->id,
                    'productive_id' => $request->productive_id[$i],
                    'productive_code' => $productive->code,
                    'amount'=>$request->amount[$i],
                    'date' => date('Y-m-d'),
                    'year' => date('Y'),
                    'month' => date('m'),
                    'publisher' => auth('admin')->user()->id,
                    'created_at'=>date('Y-m-d H:i:s'),
                    'updated_at'=>date('Y-m-d H:i:s'),

                ];

                array_push($sql,$details);
            }
            DB::table('production_details')->insert($sql);


        }


        foreach (ProductionDetails::where('production_id',$production->id)->get() as $pivot){
            $mainProductive=Productive::find($pivot->productive_id);
             $itemInstallation=ItemInstallation::where('productive_id',$pivot->productive_id)->first();
                foreach(ItemInstallationDetails::where('item_installation_id',$itemInstallation->id)->get() as $rowDetails ){
                    ProductionMaterial::create([
                        'process'=>'production',
                        'process_id'=>$production->id,
                        'main_productive_id'=>$pivot->productive_id,
                        'main_amount'=>$pivot->amount,
                        'productive_id'=>$rowDetails->productive_id,
                        'amount'=>$rowDetails->amount,
                        'all_amount'=>$pivot->amount*$rowDetails->amount,
                        'date'=>date('Y-m-d'),
                        'publisher'=>auth('admin')->user()->id,
                        'month'=>date('m'),
                        'year'=>date('Y'),
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s'),


                    ]);
                }
        }




        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }


    public function edit(  $id)
    {
        $row=Production::find($id);

        return view('Admin.CRUDS.productions.edit', compact('row'));
    }

    public function update(Request $request, $id )
    {
        $data = $request->validate([
            'storage_id' => 'required|exists:storages,id' ,
            'production_date'=>'required|date',

        ]);


        $datails = $request->validate([
            'productive_id'=>'required|array',
            'productive_id.*'=>'required',
            'amount'=>'required|array',
            'amount.*'=>'required',

        ]);

        if (count($request->amount) != count($request->productive_id) )
            return response()->json(
                [
                    'code' => 421,
                    'message' => 'المنتج مطلوب'
                ]);

        $data['publisher']=auth('admin')->user()->id;
        $data['date']=date('Y-m-d');
        $data['month']=date('m');
        $data['year']=date('Y');


        $production= Production::findOrFail($id);
        $production->update($data);


        ProductionDetails::where('production_id',$id)->delete();

        $sql=[];

        if ($request->productive_id ) {
            for ($i = 0; $i < count($request->productive_id); $i++) {

                $details = [];
                $productive = Productive::findOrFail($request->productive_id[$i]);

                $details = [
                    'production_id' => $production->id,
                    'productive_id' => $request->productive_id[$i],
                    'productive_code' => $productive->code,
                    'amount'=>$request->amount[$i],
                    'date' => $production->date,
                    'year' => $production->year,
                    'month' => $production->month,
                    'publisher' => $production->publisher,
                    'created_at'=>$production->created_at,
                    'updated_at'=>date('Y-m-d H:i:s'),

                ];

                array_push($sql,$details);
            }
            DB::table('production_details')->insert($sql);

            ProductionMaterial::where('process_id',$id)->where('process','production')->delete();


            foreach (ProductionDetails::where('production_id',$production->id)->get() as $pivot){
                $mainProductive=Productive::find($pivot->productive_id);
                $itemInstallation=ItemInstallation::where('productive_id',$pivot->productive_id)->first();

                foreach(ItemInstallationDetails::where('item_installation_id',$itemInstallation->id)->get() as $rowDetails ){
                    ProductionMaterial::create([
                        'process'=>'production',
                        'process_id'=>$production->id,
                        'main_productive_id'=>$pivot->productive_id,
                        'main_amount'=>$pivot->amount,
                        'productive_id'=>$rowDetails->productive_id,
                        'amount'=>$rowDetails->amount,
                        'all_amount'=>$pivot->amount*$rowDetails->amount,
                        'date' => $production->date,
                        'year' => $production->year,
                        'month' => $production->month,
                        'publisher' => $production->publisher,
                        'created_at'=>$production->created_at,
                        'updated_at'=>date('Y-m-d H:i:s'),


                    ]);
                }
            }





        }


        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }


    public function destroy( $id)
    {

        $row=Production::find($id);

        ProductionMaterial::where('process','production')->where('process_id',$row->id)->delete();

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!'
            ]);
    }//end fun

    public function getProductionDetails($id){
        $production=Production::findOrFail($id);
        $rows=ProductionDetails::where('production_id',$id)->with(['productive'])->get();
        return view('Admin.CRUDS.productions.parts.productionDetails',compact('rows'));
    }

    public function makeRowDetailsForProductionDetails(){
        $id=rand(2,999999999999999);
        $html=  view('Admin.CRUDS.productions.parts.details', compact('id'))->render();

        return response()->json(['status'=>true,'html'=>$html,'id'=>$id]);
    }

}
