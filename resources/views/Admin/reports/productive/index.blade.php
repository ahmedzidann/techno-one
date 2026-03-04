@extends('Admin.layouts.inc.app')
@section('title')
    حركة صنف
@endsection
@section('css')
@endsection
@section('content')
    <form id="search" action="{{ route('productive-movement.index') }}">
        <div class="row mb-3">
            <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                <!--begin::Label-->
                <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1"> المخزن</span>
                </label>
                <select id='storage_id' name="storage_id" style='width: 200px;'>
                    <option selected disabled>- ابحث عن المخزن</option>
                </select>
            </div>
            <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                <label for="product_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1">المنتج</span>
                </label>
                <select name="product_id" id='product_id' style='width: 200px;'>
                    <option selected disabled>- ابحث عن المنتج -</option>
                </select>
            </div>
            <div class="d-flex flex-column mb-7 fv-row col-sm-2">
                <label for="fromDate" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1"> تاريخ البداية </span>

                </label>
                <input type="date" id="fromDate"
                    @isset($request['fromDate']) value="{{ $request['fromDate'] }}"
                       @endisset
                    name="fromDate" class="showBonds form-control">

            </div>
            <div class="d-flex flex-column mb-7 fv-row col-sm-2">
                <label for="toDate" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1"> تاريخ النهاية </span>

                </label>
                <input type="date" id="toDate"
                    @isset($request['toDate']) value="{{ $request['toDate'] }}"
                       @endisset
                    name="toDate" class="showBonds form-control">
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
            {{-- <h5 class="card-title mb-0 flex-grow-1">فواتير المشتريات</h5> --}}


        </div>
        <div class="card-body">
            @if (request('product_id'))
                <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th> التاريخ </th>
                            <th> نوع الحركة </th>
                            <th> الكمية (بونص)</th>
                            <th> الرصيد </th>
                        </tr>
                    </thead>
                </table>
                <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:20%">
                    <tbody>
                        <tr>
                            <th> رصيد أول المدة </th>
                            <th> {{ $rasied_ayni }} </th>
                        </tr>
                        <tr>
                            <th> مبيعات </th>
                            <th> 
                                @if($sales->total_bouns)
                                {{ $sales->total_amount + $sales->total_bouns . "($sales->total_bouns)"}}                                 
                                @else
                                {{ $sales->total_amount ?? 0 }}                                 
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th> مشتريات </th>
                            <th>
                                @if($purchases->total_bouns)
                                {{ $purchases->total_amount + $purchases->total_bouns . "($purchases->total_bouns)"}}                                 
                                @else
                                {{ $purchases->total_amount ?? 0 }}                                 
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th> مرتجع مبيعات </th>
                            <th> 
                                @if($hadback_sales->total_bouns)
                                {{ $hadback_sales->total_amount + $hadback_sales->total_bouns . "($hadback_sales->total_bouns)"}}                                 
                                @else
                                {{ $hadback_sales->total_amount ?? 0 }}                                 
                                @endif
                             </th>
                        </tr>
                        <tr>
                            <th> مرتجع مشتريات</th>
                            <th>
                                @if($hadback_purchases->total_bouns)
                                {{ $hadback_purchases->total_amount + $hadback_purchases->total_bouns . "($hadback_purchases->total_bouns)"}}                                 
                                @else
                                {{ $hadback_purchases->total_amount ?? 0 }}                                 
                                @endif
                              </th>
                        </tr>
                        <tr>
                            <th> اهلاك </th>
                            <th> {{ $destruction }} </th>
                        </tr>
                        <tr>
                            <th> تسوية بالزيادة </th>
                            <th> {{ $incremental_adjustment }} </th>
                        </tr>
                        <tr>
                            <th> تسوية بالعجز </th>
                            <th> {{ $deficit_adjustment }} </th>
                        </tr>
                        <tr>
                            <th> الاجمالي </th>
                            <th> {{ $rasied_ayni + ($purchases->total_amount + $purchases->total_bouns) + ($hadback_sales->total_amount + $hadback_sales->total_bouns) - (($sales->total_amount + $sales->total_bouns ) + ($hadback_purchases->total_amount + $hadback_purchases->total_bouns) + $destruction) + $incremental_adjustment - $deficit_adjustment }}
                            </th>
                        </tr>
                    </tbody>
                </table>
            @endif

        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets_new/datatable/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>

    <script>
        var columns = [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'total_amount',
                name: 'total_amount'
            },
            {
                data: 'total',
                name: 'total'
            },
        ];
        let newUrl = '{{ route('productive-movement.index') }}';

        $(function() {
            $("#table").DataTable({
                processing: true,
                // pageLength: 50,
                paging: false,
                dom: 'Bfrltip',

                bLengthChange: true,
                serverSide: true,
                ajax: {
                    url: newUrl,
                    data: function(d) {
                        d.fromDate = '{{ request('fromDate') }}';
                        d.toDate = '{{ request('toDate') }}';
                        d.storage_id = '{{ request('storage_id') }}';
                        d.product_id = '{{ request('product_id') }}';
                    }
                },
                columns: columns,
                // order: [
                //     [0, "asc"]
                // ],
                "language": <?php echo json_encode(datatable_lang()); ?>,
                // "language": {
                //     paginate: {
                //         previous: "<i class='simple-icon-arrow-left'></i>",
                //         next: "<i class='simple-icon-arrow-right'></i>"
                //     },
                //     "sProcessing": "جاري التحميل ..",
                //     "sLengthMenu": "اظهار _MENU_ سجل",
                //     "sZeroRecords": "لا يوجد نتائج",
                //     "sInfo": "اظهار _START_ الى  _END_ من _TOTAL_ سجل",
                //     "sInfoEmpty": "لا نتائج",
                //     "sInfoFiltered": "للبحث",
                //     "sSearch": "بحث :    ",
                //     "oPaginate": {
                //         "sPrevious": "السابق",
                //         "sNext": "التالي",
                //     }
                // },
                // buttons: [
                //     'colvis',
                //     'excel',
                //     'print',
                //     'copy',
                //     'csv',
                //     // 'pdf'
                // ],

                searching: true,
                destroy: true,
                info: false,


            });

        });
    </script>
    {{-- @include('Admin.layouts.inc.ajax', ['url' => 'productive-movement']) --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function() {

            $("#storage_id").select2({
                placeholder: 'Channel...',
                // width: '350px',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.getStorages') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
        })();

        (function() {

            $("#product_id").select2({
                placeholder: 'Channel...',
                // width: '350px',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.getAllProductive') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
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
@endsection
