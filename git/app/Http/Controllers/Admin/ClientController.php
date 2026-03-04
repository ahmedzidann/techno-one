<?php

namespace App\Http\Controllers\Admin;

use App\Enum\AreaType;
use App\Enum\PaymentCategory;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Client;
use App\Models\ClientSubscription;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
 use Tymon\JWTAuth\Facades\JWTAuth;
class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:عرض العملاء,admin')->only('index');
        $this->middleware('permission:تعديل العملاء,admin')->only(['edit', 'update']);
        $this->middleware('permission:إنشاء العملاء,admin')->only(['create', 'store']);
        $this->middleware('permission:حذف العملاء,admin')->only('destroy');
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $rows = Client::query()->with(['city', 'governorate', 'subscription'])->where('status', 'approved');
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
                ->addColumn('client_type', function ($admin) {
                    if ($admin->client_type) {
                        return config('enums.client_types')[$admin->client_type];
                    }
                })
                 ->addColumn('register_type', function ($admin) {
                    if ($admin->register_type) {
                        return config('enums.register_types')[$admin->register_type];
                    }
                })

                ->addColumn('subscription', function ($admin) {
                    if ($admin->subscription) {
                        return $admin->subscription?->title . "({$admin->subscription?->discount}%)";
                    }
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.clients.index');
    }

    public function create()
    {
        $governorates = Area::where('from_id', null)->get();
        $subscriptions = ClientSubscription::get();
        $employees = Employee::get();
        $paymentCategories = PaymentCategory::getCategoriesSelect();

        return view('Admin.CRUDS.clients.parts.create', compact('governorates', 'paymentCategories', 'subscriptions', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'client_type' => 'required',
            'code' => 'required|unique:clients,code',
            'phone' => 'required|unique:clients,phone',
            'governorate_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:areas,id',
            'address' => 'nullable',
            'previous_indebtedness' => 'required|integer',
            'region_id' => 'nullable',
            'tax_card' => 'nullable',
            'password' => 'required',
            'commercial_register'=>'nullable',
            'national_num'=>'nullable'
        ]);
       

        $data['publisher'] = auth('admin')->user()->id;
         $data['password'] = Hash::make($request->password);

        Client::create($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    public function show(Client $client)
    {
        return response()->json([
            'client' => $client,
            'category' => PaymentCategory::getCategoriesSelect()[$client->payment_category],
        ]);
    }

    public function edit($id)
    {

        $row = Client::with(['representative', 'distributor'])->find($id);
        $governorates = Area::where('from_id', null)->get();
        $cities = Area::where('type', AreaType::CITY)->where('from_id', $row->governorate_id)->get();
        $regions = Area::where('type', AreaType::REGION)->where('from_id', $row->city_id)->get();
        $subscriptions = ClientSubscription::get();
        $paymentCategories = PaymentCategory::getCategoriesSelect();
        $employees = Employee::get();

        return view('Admin.CRUDS.clients.parts.edit', compact('row', 'governorates', 'cities', 'regions', 'paymentCategories', 'subscriptions', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:clients,code,' . $id,
            'phone' => 'required|unique:clients,phone,' . $id,
            'governorate_id' => 'required|exists:areas,id',
            'city_id' => 'required|exists:areas,id',
            'address' => 'nullable',
            'previous_indebtedness' => 'required|integer',
            'region_id' => 'nullable',
             'client_type' => 'required',
             'password' => 'nullable',
              'tax_card' => 'nullable',
             'commercial_register'=>'nullable',
             'national_num'=>'nullable',
        ]);
         if (!empty($data['password'])) {
          $data['password'] = Hash::make($data['password']);
         } else {
          unset($data['password']); // إزالة المفتاح لتجنب الكتابة بقيمة null
         }
       
        $row = Client::find($id);
        $row->update($data);

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    }

    public function destroy($id)
    {

        $row = Client::find($id);

        $row->delete();

        return response()->json(
            [
                'code' => 200,
                'message' => 'تمت العملية بنجاح!',
            ]
        );
    } //end fun

    public function getCitiesForGovernorate($id)
    {
        $cities = Area::where('from_id', $id)->get();
        return view('Admin.CRUDS.clients.parts.cities', compact('cities'));
    }

    public function getRegionsForCity($id)
    {
        $regions = Area::where('from_id', $id)->get();
        return view('Admin.CRUDS.clients.parts.regions', compact('regions'));
    }

    public function get_clients(Request $request)
    {
        // المستخدم الحالي من الـ JWT
        $user = JWTAuth::parseToken()->authenticate();

        $perPage = $request->get('per_page', 10);

        $clients = Client::orderBy('id', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'data' => $clients->items(),
            'pagination' => [
                'current_page' => $clients->currentPage(),
                'last_page'    => $clients->lastPage(),
                'per_page'     => $clients->perPage(),
                'total'        => $clients->total(),
            ]
        ]);
    }


    public function ClientsByStatus(Request $request, $status )
    {
    if ($request->ajax()) {
            $rows = Client::query()->with(['city', 'governorate', 'subscription'])->where('status', $status) ;
            return DataTables::of($rows)
                ->addColumn('action', function ($row) {

                    $edit = '';
                    $delete = '';

                      if($row->status=='pending')
                        {
                            $title="تحت الاجراء";
                            $class="btn btn-info";


                        }else if($row->status=='approved')
                        {
                            $title="مقبول";
                            $class="btn btn-success";
                        }elseif($row->status=='refused')
                        {
                             $title="مرفوض";
                            $class="btn btn-danger";
                        }

                return '
                          <button   type="button"
                             class="' . $class . ' openModalBtn"
                             data-id="' . $row->id . '"
                              data-status="' . $row->status . '"
                              data-reason="' . $row->reason . '"
                             data-bs-toggle="modal"
                             data-bs-target="#statusModal">

                            <span class="svg-icon svg-icon-3">
                            ' . $title . '
                            </span>

                          </button>';

            })
                ->addColumn('client_type', function ($admin) {
                    if ($admin->client_type) {
                        return config('enums.client_types')[$admin->client_type];
                    }
                })
                 ->addColumn('register_type', function ($admin) {
                    if ($admin->register_type) {
                        return config('enums.register_types')[$admin->register_type];
                    }
                })

                ->addColumn('subscription', function ($admin) {
                    if ($admin->subscription) {
                        return $admin->subscription?->title . "({$admin->subscription?->discount}%)";
                    }
                })
                ->editColumn('created_at', function ($admin) {
                    return date('Y/m/d', strtotime($admin->created_at));
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('Admin.CRUDS.clients.Clients_by_status');

    }

    public function updateStatus(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'status' => 'required|string',
    ]);

    $client = Client::find($request->client_id);
    $client->status = $request->status;
     $client->reason = $request->reason;
     $client->user_update_status =auth()->id();
    $client->time_update_status = now();
    $client->save();

    // لو عايز تحفظ السبب في جدول آخر ممكن هنا تضيفه
    // ClientStatusChangeLog::create([...]);

    return response()->json([
        'success' => true,
        'message' => 'تم تحديث الحالة بنجاح'
    ]);
}



}
