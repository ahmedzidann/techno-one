@extends('Admin.layouts.inc.app')
@section('title')
    كشف حساب عميل
@endsection
@section('css')
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0 flex-grow-1">كشف حساب</h5>
                </div>


                <div class="card-body">
                    <div class="row my-4 g-4">

                        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                            <!--begin::Label-->
                            <label for="client_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required mr-1"> العميل</span>
                            </label>
                            <select id='client_id' name="client_id" style='width: 200px;'>
                                <option selected disabled>- ابحث عن عملاء</option>
                            </select>
                        </div>

                        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                            <!--begin::Label-->
                            <label for="payment_month" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required mr-1"> الشهر</span>
                            </label>
                            <select id="payment_month" name="month" class="form-control">
                                <option selected disabled>اختر الشهر</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                        {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor

                            </select>

                        </div>

                        <div class="d-flex flex-column mb-7 fv-row col-sm-3" id="client_payment_setting">

                        </div>

                        <div class="d-flex flex-column mb-7 fv-row col-sm-3 mt-5">
                            <button form="form" class="btn btn-primary" id = "filter">
                                <span class="indicator-label">بحث</span>
                            </button>
                        </div>
                        <table class="table table-bordered" id="accountStatementTable">
                            <thead>
                                <tr>
                                    <th>مسلسل</th>
                                    <th>التاريخ</th>
                                    <th>العملية</th>
                                    <th>دائن</th>
                                    <th>مدين</th>
                                    <th>الرصيد</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>



            </div>
        </div>
    </div>
    <!-- end row -->
@endsection
@section('js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets_new/datatable/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {

            $("#client_id").select2({
                placeholder: 'Channel...',
                // width: '350px',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.getClients') }}',
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
    <script>
        $(document).on('change', '#client_id', function() {
            var client_id = $(this).val();

            $.ajax({
                type: 'GET',
                url: "{{ route('admin.customerAccountStatements') }}",
                data: {
                    client_id: client_id,
                },
                success: function(res) {

                    $('#table-container').html(res.html);


                },
                error: function(data) {
                    // location.reload();
                }
            });

        })
    </script>

    <script>
        function dataTable() {
            if (!$('#client_id').val()) {
                return alert('من فضلك اختر عميل');
            }

            // Check if the DataTable is already initialized
            if ($.fn.DataTable.isDataTable('#accountStatementTable')) {
                $('#accountStatementTable').DataTable().destroy(); // Destroy the existing instance
            }

            // Initialize the DataTable
            $('#accountStatementTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.customerAccountStatements') }}",
                    data: function(d) {
                        d.client_id = $('#client_id').val();
                        d.payment_month = $('#payment_month').val();
                        d.client_payment_setting_id = $('#client_payment_setting_id').val();
                    }
                },
                columns: [{
                        data: null, // This will be populated with the sequential number
                        name: 'serial_number',
                        searchable: false,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart +
                            1; // Generate sequential number
                        }
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'type_label',
                        name: 'type'
                    },
                    {
                        data: 'credit',
                        name: 'credit'
                    },
                    {
                        data: 'debt',
                        name: 'debt'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                ]
            });
        }

        // Trigger dataTable function on filter button click
        $('#filter').click(function() {
            dataTable();
        });
    </script>
    <script>
        $(document).on('change', '#payment_month', function() {
            let month = $(this).val();
            console.log(month);

            let url = `{{ route('admin.getClientPaymentSettings') }}`;

            // Send the data to the server
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    month: month,
                    _token: '{{ csrf_token() }}' // Include CSRF token if needed
                },
                success: function(response) {
                    // Clear previous data from the div
                    $('#client_payment_setting').empty();

                    // Create a select element
                    var selectElement = `<label for="client_payment_setting_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required mr-1">اعدادات السداد</span>
                                    </label>
                                    <select id="client_payment_setting_id" name="client_payment_setting_id" class="form-control">
                                        <option selected disabled>اختر الخيار</option>`;

                    // Assuming response is an array of options, loop through and append options
                    response.data.forEach(function(option) {
                        selectElement +=
                            `<option value="${option.id}">${option.title}</option>`;
                    });

                    // Close the select tag
                    selectElement += `</select>`;

                    // Append the new select element to the div
                    $('#client_payment_setting').append(selectElement);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // Handle any error cases here
                }
            });

        });
    </script>
@endsection
