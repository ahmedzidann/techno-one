@extends('Admin.layouts.inc.app')
@section('title')
    العملاء
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">العملاء</h5>

            <div>
                <button id="addBtn" class="btn btn-primary">اضافة عميل</button>
            </div>

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                       <th>نوع العميل</th>
                       <th>طريقة التسجيل </th>
                        <th>الكود</th>
                        <th>الهاتف</th>
                        <th>المحافظة</th>
                        <th>المدينة</th>
                        <th>المديونية السابقة </th>
                        <th>العنوان</th>
                        <th> تاريخ الانشاء</th>
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
                    <h2><span id="operationType"></span> عميل </h2>
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
                    $('.representative_id').select2({
                        dropdownParent: $('#Modal'),
                        placeholder: 'المندوب...',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('admin.getRepresentatives') }}',
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
                }, 2500);

            });

            $('#Modal').on('hidden.bs.modal', function() {
                $('.representative_id').select2('destroy'); // Use the specific class here too
            });
        });

        $(document).ready(function() {
            $('#Modal').on('shown.bs.modal', function() {
                setTimeout(() => {
                    $('.distributor_id').select2({
                        dropdownParent: $('#Modal'),
                        placeholder: 'الموزع...',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('admin.getDistributors') }}',
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
                }, 2500);

            });

            $('#Modal').on('hidden.bs.modal', function() {
                $('.distributor_id').select2('destroy'); 
            });
        });
    </script>
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
                data: 'client_type',
                name: 'client_type'
            },
             {
                data: 'register_type',
                name: 'register_type'
            },
            
            
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'governorate.title',
                name: 'governorate.title'
            },
            {
                data: 'city.title',
                name: 'city.title'
            },
            {
                data: 'previous_indebtedness',
                name: 'previous_indebtedness'
            },
            {
                data: 'address',
                name: 'address'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];
    </script>
    @include('Admin.layouts.inc.ajax', ['url' => 'clients'])

    <script>
        $(document).on('change', '#governorate_id', function() {
            var from_id = $(this).val();

            var route = "{{ route('admin.getCitiesForGovernorate', ':id') }}";
            route = route.replace(':id', from_id);

            setTimeout(function() {
                $('#city_id').load(route);
            }, 1000)
        })
        $(document).on('change', '#city_id', function() {
            var from_id = $(this).val();

            var route = "{{ route('admin.getRegionsForCity', ':id') }}";
            route = route.replace(':id', from_id);

            setTimeout(function() {
                $('#region_id').load(route);
            }, 1000)
        })
    </script>
@endsection
