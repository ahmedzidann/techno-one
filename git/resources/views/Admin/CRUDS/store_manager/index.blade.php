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
        <div class="card-body">
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
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];
    </script>
    @include('Admin.layouts.inc.ajax', ['url' => 'store-managers'])

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


            var url = "{{ route('store-managers.edit', ':id') }}";
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
@endsection
