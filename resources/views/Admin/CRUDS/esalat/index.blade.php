@extends('Admin.layouts.inc.app')
@section('title')
    الايصلات
@endsection
@section('css')
    <style>
        .select2-container {
            z-index: 100000;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">الايصلات</h5>

            <div>
                <button id="addBtn" class="btn btn-primary">اضافة ايصال</button>
            </div>

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم العميل</th>
                        <th>تاريخ الايصال</th>
                        <th> المبلغ المدفوع</th>
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
                    <h2><span id="operationType"></span> ايصال </h2>
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

    <div class="modal fade" id="Modal-client" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent-client">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType-client"></span> العملاء </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <button class="btn btn-sm btn-icon btn-active-color-primary" type="button" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                    <!--end::Close-->
                </div>
                <!--begin::Modal body-->
                <div class="modal-body py-4" id="form-load-client">

                </div>
                <!--end::Modal body-->
                <div class="modal-footer">
                    <div class="text-center">
                        <button id="closeClientSelect" type="reset" data-bs-dismiss="modal" aria-label="Close"
                            class="btn btn-light me-2">
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'client.name',
                name: 'client.name'
            },
            {
                data: 'date_esal',
                name: 'date_esal'
            },
            {
                data: 'paid',
                name: 'paid'
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
    @include('Admin.layouts.inc.ajax', ['url' => 'esalat'])

    <script>
        $('#Modal').on('shown.bs.modal', function() {
            $(document).on('change', '#type', function() {
                let data = `
                        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="bank_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> البنك</span>
            </label>
            <select name="bank_id"  id='bank_id' style='width: 100%;'>
                <option selected disabled>اختر البنك</option>
            </select>
        </div>
                        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="cheque_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الشيك</span>
            </label>
            <!--end::Label-->
            <input id="cheque_number" min="1" required type="number" class="form-control form-control-solid"
                name="cheque_number" value="" />
        </div>
                        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="cheque_issue_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">تاريخ الاصدار</span>
            </label>
            <!--end::Label-->
            <input id="cheque_issue_date" required type="date" class="form-control form-control-solid" name="cheque_issue_date"
                value="{{ date('Y-m-d') }}" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="cheque_due_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">تاريخ الاستحقاق</span>
            </label>
            <!--end::Label-->
            <input id="cheque_due_date" required type="date" class="form-control form-control-solid" name="cheque_due_date"
                value="{{ date('Y-m-d') }}" />
        </div>`;
                if ($(this).val() == 2) {
                    $('#cheque_data').html(data);
                    banks();
                } else {
                    $('#cheque_data').empty();
                }
            })

            function banks() {
                $("#bank_id").select2({
                    placeholder: 'Channel...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getAllBanks') }}',
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
            }

        });
    </script>
    <script>
        $(document).on('click', '.changeClientDiv', function(e) {
            e.preventDefault();
            $('#Modal').modal('hide')
            $('#form-load-client').html(loader_form)
            $('#operationType-client').text('تحديد');

            $('#Modal-client').modal('show')

            setTimeout(function() {
                $('#form-load-client').load("{{ route('admin.getClientForEsalat') }}")
            }, 1000)


        })
    </script>

    <script>
        $(document).on('change', '#client_select', function() {
            var id = $(this).val();
            $('#Modal').modal('show')
            $('#Modal-client').modal('hide')
            $('#client_id').val(id)

            var route = "{{ route('admin.getClientNameForEsalat', ':id') }}";
            route = route.replace(':id', id);


            $.ajax({
                type: 'GET',
                url: route,

                success: function(res) {

                    $('#changeClientId').val(res.name);

                },
                error: function(data) {
                    // location.reload();
                }
            });



        })
    </script>

    <script>
        $(document).on('click', '#closeClientSelect', function() {
            $('#Modal').modal('show')

        })
    </script>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
