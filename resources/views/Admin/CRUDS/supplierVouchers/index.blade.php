@extends('Admin.layouts.inc.app')
@section('title')
    ايصلات الموردين
@endsection
@section('css')
    <style>
        .select2-container{
            z-index:100000;
        }

    </style>

@endsection
@section('content')

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">ايصلات الموردين</h5>

                <div>
                    <button id="addBtn" class="btn btn-primary">اضافة  ايصال للمورد</button>
                </div>

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                   style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المورد</th>
                    <th>تاريخ  الايصال</th>
                    <th>  المبلغ المدفوع</th>
                    <th> تاريخ الانشاء</th>
                    <th>العمليات</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="Modal"  aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType"></span> ايصال </h2>
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

    <div class="modal fade" id="Modal-supplier"  aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent-supplier">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType-supplier"></span>  الموردين </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <button class="btn btn-sm btn-icon btn-active-color-primary" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                    <!--end::Close-->
                </div>
                <!--begin::Modal body-->
                <div class="modal-body py-4" id="form-load-supplier">

                </div>
                <!--end::Modal body-->
                <div class="modal-footer">
                    <div class="text-center">
                        <button id="closeSupplierSelect" type="reset" data-bs-dismiss="modal" aria-label="Close" class="btn btn-light me-2">
                            الغاء
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
            {data: 'supplier.name', name: 'supplier.name'},
            {data: 'voucher_date', name: 'voucher_date'},
            {data: 'paid', name: 'paid'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('Admin.layouts.inc.ajax',['url'=>'supplier_vouchers'])

    <script>
        $(document).on('click','.changeSupplierDiv',function (e){
            e.preventDefault();
            $('#Modal').modal('hide')
            $('#form-load-supplier').html(loader_form)
            $('#operationType-supplier').text('تحديد');

            $('#Modal-supplier').modal('show')

            setTimeout(function (){
                $('#form-load-supplier').load("{{route("admin.getSupplierForVouchers")}}")
            },1000)


        })
    </script>

    <script>
        $(document).on('change','#supplier_select',function (){
            var id=$(this).val();
            $('#Modal').modal('show')
            $('#Modal-supplier').modal('hide')
             $('#supplier_id').val(id)

            var route="{{route('admin.getSupplierNameForVouchers',':id')}}";
            route=route.replace(':id',id);


            $.ajax({
                type: 'GET',
                url: route,

                success: function (res) {

                    $('#changeSupplierId').val(res.name);

                },
                error: function (data) {
                    // location.reload();
                }
            });



        })
    </script>

    <script>
        $(document).on('click','#closeSupplierSelect',function (){
            $('#Modal').modal('show');

        })
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



@endsection
