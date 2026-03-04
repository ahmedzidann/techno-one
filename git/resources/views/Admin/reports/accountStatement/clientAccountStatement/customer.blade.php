@extends('Admin.layouts.inc.app')
@section('title', 'كشف حساب الكوبونات')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('assets_new/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">كشف حساب الكوبونات</h5>
            </div>

            <div class="card-body">
                <div class="row my-4 g-4">

                    <div class="col-sm-3 fv-row">
                        <label class="fs-6 fw-bold form-label mb-2">العميل</label>
                        <select id="client_id" style="width:200px;">
                            <option selected disabled>- ابحث عن عميل</option>
                        </select>
                    </div>

                    <div class="col-sm-3" style="padding-bottom: 40px;">
                        <button type="button" class="btn btn-primary form-control" id="filter">بحث</button>
                    </div>

                    <table class="table table-bordered" id="accountStatementTable">
                        <thead>
                            <tr>
                                <th>مسلسل</th>
                                <th>التاريخ</th>
                                <th>العملية</th>
                                <th>العميل</th>
                                <th>دائن</th>
                                <th>مدين</th>
                                <th>الرصيد</th>
                                <th>رقم الفاتورة</th>
                                <th>الملاحظات</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {

    // Select2
    $("#client_id").select2({
        placeholder: 'ابحث عن العميل',
        allowClear: true,
        ajax: {
            url: '{{ route("admin.getClients") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return { term: params.term || '', page: params.page || 1 };
            },
            cache: true
        }
    });

    let table;

    $('#filter').click(function() {

        if (!$('#client_id').val()) {
            alert('من فضلك اختر عميل');
            return;
        }

        if (table) {
            table.ajax.reload();
            return;
        }

        table = $('#accountStatementTable').DataTable({

            processing: true,
            serverSide: true,

            dom: 'Blfrtip',

            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "الكل"]
            ],

            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'تصدير Excel',
                    title: 'كشف حساب الكوبونات',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'تصدير PDF',
                    title: 'كشف حساب الكوبونات',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ],

            ajax: {
                url: "{{ route('admin.customerAccountState') }}",
                data: function(d) {
                    d.client_id = $('#client_id').val();
                }
            },

            columns: [
                { 
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta){
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'date' },
                { data: 'type_label' },
                { data: 'other_user_name' },
                { data: 'credit', render: $.fn.dataTable.render.number(',', '.', 2) },
                { data: 'debt', render: $.fn.dataTable.render.number(',', '.', 2) },
                { data: 'balance', render: $.fn.dataTable.render.number(',', '.', 2) },
                { data: 'invoice_number' },
                { data: 'notes' }
            ],

            drawCallback: function(settings) {

                let previous = settings.json ? settings.json.previous : 0;

                if ($('#previous_row').length == 0) {
                    $('#accountStatementTable tbody').prepend(`
                        <tr id="previous_row" style="background-color:#f0f0f0;font-weight:bold;">
                            <td>--</td>
                            <td>--</td>
                            <td>رصيد سابق</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>${previous}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    `);
                } else {
                    $('#previous_row td:eq(6)').text(previous);
                }
            }

        });

    });

});
</script>
@endsection