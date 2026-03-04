@extends('Admin.layouts.inc.app')
@section('title')
    فواتير المشتريات
@endsection
@section('css')
@endsection
@section('content')
    <form id="search" action="{{route('purchasesBills.index')}}">
        <div class="row mb-3">
            <div class="col-md-2 ">
                <label for="fromDate" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1"> تاريخ البداية    </span>

                </label>
                <input type="date" id="fromDate" @isset($request['fromDate']) value="{{$request['fromDate']}}"
                       @endisset name="fromDate" class="showBonds form-control">

            </div>
            <div class="col-md-2">
                <label for="toDate" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">   تاريخ النهاية    </span>

                </label>
                <input type="date" id="toDate" @isset($request['toDate']) value="{{$request['toDate']}}"
                       @endisset name="toDate" class="showBonds form-control">
            </div>

            <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                <!--begin::Label-->
                <label for="supplier_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">   المورد</span>
                </label>
                <select id='supplier_id' name="supplier_id" style='width: 200px;'>
                    <option disabled selected>ابحث عن مورد</option>
                    @isset($request['supplier_id'])
                        <option selected value="{{$request['supplier_id']}}">  {{\App\Models\Supplier::find($request['supplier_id'])->name??''}}</option>

                    @endisset
                </select>
            </div>
            <div class="d-flex flex-column mb-7 fv-row col-sm-1 ">

                <button form="search" type="submit" class="btn btn-primary my-3">
                    <span class="indicator-label ">بحث</span>
                </button>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">فواتير المشتريات</h5>


        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                   style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th> تاريخ الطلب</th>
                    <th>المورد</th>
                    <th>الاجمالي</th>
                    <th>المدفوع</th>
                    <th>المتبقي</th>
                    <th> تاريخ الانشاء</th>
                    <th>التفاصيل</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType"></span> عملية شراء </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <button class="btn btn-sm btn-icon btn-active-color-primary" type="button" data-bs-dismiss="modal"
                            aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                    <!--end::Close-->
                </div>
                <!--begin::Modal body-->
                <div class="modal-body py-4" id="form-load">

                </div>
                <!--end::Modal body-->
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="reset" data-bs-dismiss="modal" aria-label="Close" class="btn btn-light me-2">
                            الغاء
                        </button>
                        <button form="form" type="submit" id="submit" class="btn btn-primary">
                            <span class="indicator-label">اتمام</span>
                        </button>
                    </div>
                </div>
            </div>

            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>

@endsection
@section('js')

    <script>
        var columns = [
            {data: 'id', name: 'id'},
            // {data: 'purchases_number', name: 'purchases_number'},
            {data: 'purchases_date', name: 'purchases_date'},
            {data: 'supplier.name', name: 'supplier.name'},
            {data: 'total', name: 'total'},
            {data: 'paid', name: 'paid'},
            {data: 'remain', name: 'remain'},
            {data: 'created_at', name: 'created_at'},
            {data: 'details', name: 'details'},
        ];
    </script>
    @include('Admin.layouts.inc.ajax',['url'=>'purchasesBills'])
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        (function () {

            $("#supplier_id").select2({
                placeholder: 'Channel...',
                // width: '350px',
                allowClear: true,
                ajax: {
                    url: '{{route('admin.getSupplier')}}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
        })();

    </script>
    <script>
        $(document).on('click', '.showDetails', function () {
            var id = $(this).attr('data-id');
            var route = "{{route('admin.getPurchasesDetails',':id')}}";
            route = route.replace(':id', id);
            $('#form-load').html(loader_form)
            $('#operationType').text('{{trans('عرض')}}');

            $('#Modal').modal('show')

            setTimeout(function () {
                $('#form-load').load(route)
            }, 1000)
        })
    </script>
    <script>
        $(document).on('click', '.editBtn-p', function () {
            var id = $(this).data('id');


            var url = "{{route("purchases.edit",':id')}}";
            url = url.replace(':id', id)

            window.location.href = url;

        })
    </script>

@endsection
