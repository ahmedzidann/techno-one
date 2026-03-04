@extends('Admin.layouts.inc.app')
@section('title')
    طلبات الشراء
@endsection
@section('css')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">طلبات الشراء</h5>

            <div>
                <a href="{{ route('purchases-requests.create') }}" class="btn btn-primary">اضافة طلب شراء</a>
            </div>

        </div>
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
                        <th>المورد</th>
                        <th>رقم فاتورة المورد</th>
                        <th> الاجمالي قبل الخصم</th>
                        <th> نسبة الخصم</th>
                        <th> الاجمالي بعد الخصم</th>
                        <th> تاريخ الانشاء</th>
                        <th>المشتريات</th>
                        <th>تغيير الحالة</th>
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
                    <h2><span id="operationType"></span> طلب شراء </h2>
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
                data: 'purchases_date',
                name: 'purchases_date'
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
                data: 'supplier_name',
                name: 'supplier_name'
            },
            {
                data: 'supplier_fatora_number',
                name: 'supplier_fatora_number'
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
    @include('Admin.layouts.inc.ajax', ['url' => 'purchases'])

    <script>
        $(document).on('click', '.showDetails', function() {
            var id = $(this).attr('data-id');
            var route = "{{ route('admin.getPurchasesDetails', ':id') }}";
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


            var url = "{{ route('purchases.edit', ':id') }}";
            url = url.replace(':id', id)

            window.location.href = url;

        })
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('change', '.is-prepared-toggle', function() {
                let rowId = $(this).data('id');
                let url = '{{ route('update.purchase-status', ['id' => ':id']) }}';
                url = url.replace(':id', rowId);
                $.ajax({
                    url: url, // Replace with your actual update URL
                    method: 'GET',
                    success: function(response) {
                        setTimeout(() => {
                            toastr.success('تم تغيير حالة طلب الشراء بنجاح')
                        }, 1000);
                        $("#table").DataTable().draw();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating is_prepared status:', error);
                        alert('Failed to update status. Please try again.');
                        // Revert the switch if the update failed
                        $(this).prop('checked', !isPrepared);
                    }
                });
            });
        });
    </script>
@endsection
