@extends('Admin.layouts.inc.app')
@section('title')
    الاصناف
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
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
            <div class="d-flex flex-column mb-7 fv-row col-sm-1 ">

                <button id="search" type="submit" class="btn btn-primary my-3">
                    <span class="indicator-label ">بحث</span>
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th> الكود</th>
                        <th> الوحدة</th>
                        <th> التصنيف</th>
                        <th> الشركة</th>
                        <th> سعر الجمهور</th>
                        <th> حد الاعتماد</th>
                        <th>الرصيد المتبقي</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType"></span> صنف </h2>
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
    </div> --}}
@endsection
@section('js')
    <script src="{{ URL::asset('assets_new/datatable/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'unit.title',
                name: 'unit.title'
            },
            {
                data: 'category.title',
                name: 'category.title'
            },
            {
                data: 'company.title',
                name: 'company.title'
            },
            {
                data: 'audience_price',
                name: 'audience_price'
            },
            {
                data: 'limit_for_request',
                name: 'limit_for_request'
            },
            {
                data: 'remainder',
                name: 'remainder'
            },
        ];

        let newUrl = '{{ route('admin.products-low-balance') }}';

        $(function() {
          let table =  $("#table").DataTable({
                processing: true,
                paging: true,
                dom: 'Bfrltip',

                bLengthChange: true,
                serverSide: true,
                ajax: {
                    url: newUrl,
                    data: function(d) {
                        d.storage_id = $('#storage_id').val();
                        d.product_id = $('#product_id').val();
                    }
                },
                columns: columns,
                searching: true,
                destroy: true,
                info: false,
            });
            
            $('#search').on('click', function() {
                table.ajax.reload();
            });
        });
    </script>
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
