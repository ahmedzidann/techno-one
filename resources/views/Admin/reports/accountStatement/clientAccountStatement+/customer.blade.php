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
                        <div class="col-md-2 ">
                            <label for="fromDate" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required mr-1"> تاريخ البداية </span>

                            </label>
                            <input type="date" id="fromDate"
                                @isset($request['fromDate']) value="{{ $request['fromDate'] }}"  @endisset
                                name="fromDate" class="showBonds form-control">

                        </div>
                        <div class="col-md-2">
                            <label for="toDate" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required mr-1"> تاريخ النهاية </span>

                            </label>
                            <input type="date" id="toDate"
                                @isset($request['toDate']) value="{{ $request['toDate'] }}"@endisset name="toDate"
                                class="showBonds form-control">
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
                                <tr style="display: none; background-color: white; color: black;" id="initial_balance">
                                    <td>-----</td>
                                    <td>-----</td>
                                    <td>مديونية سابقة</td>
                                    <td id="prvious_credit"></td>
                                    <td id="prvious_debit"></td>
                                    <td id="previous_total"></td>
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
        function dataTable() {
            if (!$('#client_id').val()) {
                alert('من فضلك اختر عميل');
            }
            if ($.fn.DataTable.isDataTable('#accountStatementTable')) {
                $('#accountStatementTable').DataTable().destroy(); // Destroy the existing instance
            }

            var table = $('#accountStatementTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.customerAccountState') }}",
                    data: function(d) {
                        d.client_id = $('#client_id').val();
                        d.fromDate = $('#fromDate').val();
                        d.toDate = $('#toDate').val();
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
                ],
                "drawCallback": function(response) {
                    $('#initial_balance').show();
                    $('#prvious_credit').empty();
                    $('#prvious_debit').empty();
                    $('#previous_total').empty();
                    if (response.json && response.json.previous) {
                        if (response.json.previous <= 0) {
                            $('#prvious_credit').html(Math.abs(response.json.previous));
                        } else {
                            $('#prvious_debit').html(response.json.previous);

                        }
                        $('#previous_total').html(response.json.previous);

                    }

                }
            });

        }
        $('#filter').click(function() {
            dataTable();
        });
    </script>
@endsection
