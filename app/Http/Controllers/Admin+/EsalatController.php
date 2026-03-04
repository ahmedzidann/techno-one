<?php

namespace App\Http\Controllers\Admin;

use App\Enum\ChequeStatus;
use App\Enum\EsaleType;
use App\Enum\PaymentCategory;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Client;
use App\Models\Esalat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class EsalatController extends Controller
{
    //
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $rows = Esalat::query()->with(['client']);
            return DataTables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';

                    return '

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
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);

        }

        return view('Admin.CRUDS.esalat.index');
    }

    public function create()
    {
        $banks = Bank::all();
        $lastEsal = Esalat::orderBy('id', 'desc')->first();
        return view('Admin.CRUDS.esalat.parts.create', compact('banks','lastEsal'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'paid' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'date_esal' => 'required|date',
            'payment_category' => 'required',
            'client_payment_setting_id' => [Rule::requiredIf($request->payment_category != PaymentCategory::ON_DELIVERED->value)],
            'notes' => 'nullable',
            'type' => 'required|in:1,2',
            'bank_id' => 'required_if:type,2|exists:banks,id',
            'cheque_number' => 'required_if:type,2|numeric',
            'rkm_esal' => 'nullable|numeric|unique:esalats,rkm_esal',
           'dafter_rkm_esal' => 'nullable|numeric|unique:esalats,dafter_rkm_esal',
            'cheque_issue_date' => 'required_if:type,2|date',
            'cheque_due_date' => 'required_if:type,2|date',
        ]);

        $client = Client::findOrFail($request->client_id);
        if ($client->previous_indebtedness < $request->paid) {
            return response()->json(
                [
                    'code' => 421,
                    'message' => 'قيمة الايصال اكبر من المديونية',
                ]);
        }

        $data['publisher'] = auth('admin')->user()->id;
        $data['year'] = date('Y');
        $data['month'] = date('m');
        $data['date'] = date('Y-m-d');
        if ($data['type'] == EsaleType::ESALE->value) {
            $data['cheque_status'] = ChequeStatus::ACCEPTED->value;
        }
        Esalat::create($data);

        $dept = $client->previous_indebtedness;

        $client->update([
            'previous_indebtedness' => $dept - $request->paid,
        ]);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    }

    public function destroy($id)
    {

        $row = Esalat::find($id);
        $paid = $row->paid;
        $client = Client::findOrFail($row->client_id);

        $row->delete();
        $previous_indebtedness = $client->previous_indebtedness + $paid;
        $client->update([
            'previous_indebtedness' => $previous_indebtedness,
        ]);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]);
    } //end fun

    public function getClientForEsalat()
    {
        $clients = Client::get();
        return view('Admin.CRUDS.esalat.parts.clients', compact('clients'));

    }

    public function getClientNameForEsalat($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['status' => true, 'name' => $client->name, 'id' => $client->id]);
    }
//    public function getClients(Request $request){
//        if ($request->ajax())
//        {
//            $page = $request->page;
//            $resultCount = 7;
//
//            $offset = ($page - 1) * $resultCount;
//
//            $breeds = Client::where('name', 'LIKE',  '%' .$request->searchTerm. '%')->orderBy('name')->skip($offset)->take($resultCount)->get(['id',DB::raw('name as name')]);
//
//            $count = Client::count();
//
//            $clients=$breeds;
//           // $endCount = $offset + $resultCount;
//
//            $data = array();
//            foreach($clients as $client){
//                if ($client['id'] == 1){
//                    $selected=true;
//                }else{
//                    $selected=false;
//
//                }
//                $data[] = array("id"=>$client['id'], "text"=>$client['name'],'selected'=>$selected);
//            }
//
//
//            echo json_encode(array('items'=>$data,'total'=>$count));
//
//
//
//        }
//    }

    public function getClients(Request $request)
    {
        if ($request->ajax()) {

            $term = trim($request->term);
            $posts = DB::table('clients')->select('id', 'name as text')
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

    public function getClients2(Request $request)
    {

        $searchTerm = $request->searchTerm;
        $client_id = $request->client_id;

        $clients = Client::paginate(2);
        $clients_list = Client::count();
        $data = array();
        foreach ($clients as $client) {
            if ($client['id'] == $client_id) {
                $selected = true;
            } else {
                $selected = false;

            }
            $data[] = array("id" => $client['id'], "text" => $client['name'], 'selected' => $selected);
        }

        echo json_encode(array('items' => $data, 'total' => $clients_list));

    }

    public function testing()
    {
        return view('testing');
    }

}
