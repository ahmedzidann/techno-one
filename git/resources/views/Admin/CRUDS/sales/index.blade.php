@extends('Admin.layouts.inc.app')
@section('title')
    المبيعات
@endsection
@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">المبيعات</h5>

            <div>
                <a href="{{ route('sales.create') }}" class="btn btn-primary">اضافة عملية بيع</a>
            </div>

        </div>
        <div class="card-body">
            <div class="row my-4 g-4">

                <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                    <label for="from_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span class="required mr-1">من تاريخ:</span>
                    </label>
                    <input id="from_date" required type="date" class="form-control form-control-solid" name="from_date"
                        value="" />
                </div>

                <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                    <label for="to_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span class="required mr-1">إلى تاريخ:</span>
                    </label>
                    <input id="to_date" required type="date" class="form-control form-control-solid" name="to_date"
                        value="" />
                </div>
                <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                    <label for="representative_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span class="required mr-1"> المندوب</span>
                    </label>
                    <select id="representative_id" name="representative_id" style="width: 200px;">
                    </select>
                </div>

                <div class="d-flex flex-column mb-7 fv-row col-sm-3 mt-5">
                    <button form="form" class="btn btn-primary" id = "filter">
                        <span class="indicator-label">بحث</span>
                    </button>
                </div>

                <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            {{--                    <th>رقم الطلب</th> --}}
                            <th> تاريخ الطلب</th>
                            <th>المخزن</th>
                            <th>طريقة الدفع</th>
                            <th>العميل</th>
                            <th>رقم الفاتورة</th>
                            <th> الاجمالي قبل الخصم</th>
                            <th> نسبة الخصم</th>
                            <th> الاجمالي بعد الخصم</th>
                            <th> تاريخ الانشاء</th>
                            <th>التفاصيل</th>
                            <th> الحالة </th>
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
                        <h2><span id="operationType"></span> عملية بيع </h2>
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
        <script src="{{ URL::asset('assets_new/datatable/feather.min.js') }}"></script>
        <script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>
        <script>
            var columns = [{
                    data: 'id',
                    name: 'id'
                },
                // {data: 'purchases_number', name: 'purchases_number'},
                {
                    data: 'sales_date',
                    name: 'sales_date'
                },
                {
                    data: 'storage.title',
                    name: 'storage.title'
                },
                {
                    data: 'pay_method',
                    name: 'pay_method'
                },
                {
                    data: 'client.name',
                    name: 'client.name'
                },
                {
                    data: 'fatora_number',
                    name: 'fatora_number'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'total_discount',
                    name: 'total_discount'
                },
                {
                    data: 'total_after_discount',
                    name: 'total_after_discount'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'details',
                    name: 'details'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ];
        </script>
        @include('Admin.layouts.inc.ajax', ['url' => 'sales'])

        <script>
            $(document).on('click', '.showDetails', function() {
                var id = $(this).attr('data-id');
                var route = "{{ route('admin.getSalesDetails', ':id') }}";
                route = route.replace(':id', id);
                $('#form-load').html(loader_form)
                $('#operationType').text('{{ trans('عرض') }}');

                $('#Modal').modal('show')

                setTimeout(function() {
                    $('#form-load').load(route)
                }, 1000)
            })
        </script>
        <script>
            $(document).on('click', '.editBtn-p', function() {
                var id = $(this).data('id');


                var url = "{{ route('sales.edit', ':id') }}";
                url = url.replace(':id', id)

                window.location.href = url;

            })

            $(document).ready(function() {
                $(document).on('click', '.dropdown-item', function(e) {
                    e.preventDefault();
                    let newStatus = $(this).data('status');
                    let rowId = $(this).data('row-id');
                    let button = $(this).closest('.dropdown').find('button');
                    let url = '{{ route('admin.update-sales-status') }}';

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            id: rowId,
                            status: newStatus
                        },
                        success: function(response) {
                            // Update button text and class
                            button.text($(e.target).text());
                            button.removeClass(
                                'badge-primary badge-info badge-success badge-danger');
                            button.addClass(getStatusClass(newStatus));
                            $('#table').DataTable().ajax.reload(null, false);
                            toastr.success('status updated successfully');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating status:', error);
                            alert('Failed to update status. Please try again.');
                        }
                    });
                });

                function getStatusClass(status) {
                    switch (status) {
                        case 'new':
                            return 'badge-primary';
                        case 'in_progress':
                            return 'badge-info';
                        case 'complete':
                            return 'badge-success';
                        case 'canceled':
                            return 'badge-danger';
                        default:
                            return 'badge-secondary';
                    }
                }
            });
        </script>
        <script>
            (function() {

                $("#representative_id").select2({
                    placeholder: 'Channel...',
                    // width: '350px',
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
            })();
        </script>
    @endsection
