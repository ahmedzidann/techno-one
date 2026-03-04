@extends('Admin.layouts.inc.app')
@section('title')
    المبيعات
@endsection
@section('css')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        {{--                    <th>رقم الطلب</th> --}}
                        <th> تاريخ الطلب</th>
                        <th>المخزن</th>
                        <th>العميل</th>
                        <th>رقم الفاتورة</th>
                        <th> تاريخ الانشاء</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-xl mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType"></span> </h2>
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
                        {{-- <button form="form" type="submit" id="submit" class="btn btn-primary">
                            <span class="indicator-label">اتمام</span>
                        </button> --}}
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
                data: 'client.name',
                name: 'client.name'
            },
            {
                data: 'fatora_number',
                name: 'fatora_number'
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
    @include('Admin.layouts.inc.ajax', ['url' => 'prepare-items'])
    <script>
        $(document).on('click', '.showDetails', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var route = "{{ route('prepare-items.edit', ':id') }}";
            route = route.replace(':id', id);
            $.ajax({
                url: route, // The URL to your edit route
                type: 'GET',
                success: function(response) {
                    $('#form-load').html(response.view); // Load the form into the modal
                    $('#operationType').html(` تحضير فاتورة رقم` + response.row
                        .fatora_number); // Load the form into the modal
                    $('#Modal').modal('show'); // Show the modal
                },
                error: function() {
                    alert('Error loading form');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('change', '.is-prepared-toggle', function() {
                var isPrepared = $(this).prop('checked') ? 1 : 0;
                var rowId = $(this).data('id');
                var amount = $('#amount-' + rowId).val();
                var notes = $('#notes-' + rowId).val();
                var batch_number = $('#batch_number-' + rowId).val();

                let url = '{{ route('update.prepare-status') }}';

                $.ajax({
                    url: url, // Replace with your actual update URL
                    method: 'POST',
                    data: {
                        id: rowId,
                        amount: amount,
                        notes: notes,
                        batch_number: batch_number,
                        is_prepared: isPrepared
                    },
                    success: function(response) {
                        if (isPrepared == 1) {
                            toastr.success('تم تحضير الصنف بنجاح')
                        } else {
                            toastr.success('تم إلغاء تحضير الصنف بنجاح')

                        }
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
    <script>
        $(document).ready(function() {
            // Initialize Select2 inside the modal once it's shown
            $('#Modal').on('shown.bs.modal', function() {
                // Initialize the select2 elements with modal as dropdown parent
                $('.select2').select2({
                    dropdownParent: $('#Modal')
                });
                $(`.batch_number`).select2({
                    dropdownParent: $('#Modal'),
                    placeholder: 'رقم التشغيلة...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getBatches') }}',
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
            });

            // Optional: Destroy select2 if the modal is hidden to avoid reinitialization issues
            $('#Modal').on('hidden.bs.modal', function() {
                $('.select2').select2('destroy');
            });
        });
    </script>
@endsection
