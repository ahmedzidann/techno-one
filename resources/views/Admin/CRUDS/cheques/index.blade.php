@extends('Admin.layouts.inc.app')
@section('title')
    ادارة الشيكات
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs" id="chequesTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab1" href="#tab1">جاري التنفيذ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tab2" href="#tab2">تم التنفيذ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#tab3" href="#tab3">مرفوض</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab1">
                    <table id="table1" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العميل</th>
                                <th>القيمة</th>
                                <th>البنك</th>
                                <th>رقم الشيك</th>
                                <th>تاريخ اصدار الشيك</th>
                                <th>تاريخ استحقاق الشيك</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_tab1"></tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab2">
                    <table id="table2" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العميل</th>
                                <th>القيمة</th>
                                <th>البنك</th>
                                <th>رقم الشيك</th>
                                <th>تاريخ اصدار الشيك</th>
                                <th>تاريخ استحقاق الشيك</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="tab3">
                    <table id="table3" class="table table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العميل</th>
                                <th>القيمة</th>
                                <th>البنك</th>
                                <th>رقم الشيك</th>
                                <th>تاريخ اصدار الشيك</th>
                                <th>تاريخ استحقاق الشيك</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets_new/datatable/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Define columns for all tables
            var columns = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'client.name',
                    name: 'client.name'
                },
                {
                    data: 'paid',
                    name: 'paid'
                },
                {
                    data: 'bank.name',
                    name: 'bank.name'
                },
                {
                    data: 'cheque_number',
                    name: 'cheque_number'
                },
                {
                    data: 'cheque_issue_date',
                    name: 'cheque_issue_date'
                },
                {
                    data: 'cheque_due_date',
                    name: 'cheque_due_date'
                },
                {
                    data: 'cheque_status',
                    name: 'cheque_status'
                },
            ];

            var tableInstances = {}; // Store DataTable instances for each tab

            // Function to initialize a DataTable
            function initializeTable(tableId, status) {
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().clear().destroy(); // Clear and destroy existing instance
                }

                tableInstances[tableId] = $(tableId).DataTable({
                    ajax: {
                        url: '{{ route('cheques.index') }}',
                        data: {
                            status: status
                        },
                    },
                    columns: columns,
                    processing: true,
                    serverSide: true,
                });
            }

            // Initialize the first tab (default active tab)
            initializeTable('#table1', 1);

            // Handle tab switching
            $('#chequesTabs a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("data-bs-target");

                switch (target) {
                    case '#tab1':
                        initializeTable('#table1', 1);
                        break;
                    case '#tab2':
                        initializeTable('#table2', 2);
                        break;
                    case '#tab3':
                        initializeTable('#table3', 3);
                        break;
                }
            });
        });


        function change_cheque_status(element, id) {
            let rowId = id;
            let status = $(element).val();

            $.ajax({
                url: '{{ route('admin.changeStatusChequeStatus') }}',
                type: 'POST',
                data: {
                    id: rowId,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Handle success
                    if (response.code == 200) {
                        toastr.success('تم تغيير حالة الشيك بنجاح');
                        window.location.reload(true);
                    } else {
                        toastr.error('حدث خطأ ما!');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    toastr.error('حدث خطأ ما!');
                }
            });
        }
    </script>
@endsection
