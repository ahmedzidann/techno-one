@extends('Admin.layouts.inc.app')
@section('title')
    المرتجعات
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">المرتجعات</h5>

                <div>
                    <a href="{{route('head_back_purchases.create')}}" class="btn btn-primary">اضافة عملية ارجاع</a>
                </div>

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                   style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
{{--                    <th>رقم الطلب</th>--}}
                    <th> تاريخ الطلب</th>
                    <th>المخزن</th>
                    <th>طريقة الدفع</th>
                    <th>المورد</th>
                    <th>رقم الفاتورة</th>
                    <th>رقم فاتورة المورد</th>
                    <th>الاجمالي</th>
                    <th> تاريخ الانشاء</th>
                    <th>المشتريات</th>
                    <th>العمليات</th>
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
                    <h2><span id="operationType"></span> عملية ارجاع </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <button class="btn btn-sm btn-icon btn-active-color-primary" type="button" data-bs-dismiss="modal" aria-label="Close">
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
            {data: 'storage.title', name: 'storage.title'},
            {data: 'pay_method', name: 'pay_method'},
            {data: 'supplier.name', name: 'supplier.name'},
            {data: 'fatora_number', name: 'fatora_number'},
            {data: 'supplier_fatora_number', name: 'supplier_fatora_number'},
            {data: 'total', name: 'total'},
            {data: 'created_at', name: 'created_at'},
            {data: 'details', name: 'details'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('Admin.layouts.inc.ajax',['url'=>'head_back_purchases'])

    <script>
        $(document).on('click','.showDetails',function (){
         var id=$(this).attr('data-id');
            var route="{{route('admin.getHeadBackPurchasesDetails',':id')}}";
            route=route.replace(':id',id);
            $('#form-load').html(loader_form)
            $('#operationType').text('{{trans('عرض')}}');

            $('#Modal').modal('show')

            setTimeout(function (){
                $('#form-load').load(route)
            },1000)
        })
    </script>
    <script>
        $(document).on('click','.editBtn-p',function (){
            var  id = $(this).data('id');


            var url = "{{route("head_back_purchases.edit",':id')}}";
            url = url.replace(':id',id)

            window.location.href=url;

        })
    </script>

@endsection
