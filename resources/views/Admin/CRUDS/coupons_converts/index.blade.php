@extends('Admin.layouts.inc.app')
@section('title')
    تسويات الملاء
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> اضافة رصيد الكوبونات</h5>

            <div>
                <button id="addBtn" class="btn btn-primary">اضافة رصيد الكوبونات</button>
            </div>

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                       <th> تاريخ الانشاء</th>
                        <th>من</th>
                        <th>الي</th>
                         <th>القيمه</th>
                        <th>الملاحظات</th>
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
                    <h2><span id="operationType"></span> تسوية عميل </h2>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#Modal').on('shown.bs.modal', function() {
                setTimeout(() => {
                    $('.client_id').select2({
                        dropdownParent: $('#Modal'),
                        placeholder: 'العملاء...',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('admin.gettraders') }}',
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
                }, 1000);

            });

            $('#Modal').on('hidden.bs.modal', function() {
                $('.representative_id').select2('destroy'); // Use the specific class here too
            });
        });
    </script>
    <script>
        var columns = [{
                data: 'id',
                name: 'id'
            },
           {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'from_user',
                name: 'from_user'
            },
            {
                data: 'to_user',
                name: 'to_user'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'notes',
                name: 'notes'
            },
            
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];
    </script>
    @include('Admin.layouts.inc.ajax', ['url' => 'coupons-converts'])
@endsection
