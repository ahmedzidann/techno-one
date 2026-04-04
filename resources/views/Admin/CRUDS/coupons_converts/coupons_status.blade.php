@extends('Admin.layouts.inc.app')
@section('title')
    تسويات الملاء
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> اضافة رصيد الكوبونات</h5>

            <!-- <div>
                <button id="addBtn" class="btn btn-primary">اضافة رصيد الكوبونات</button>
            </div> -->

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                       <th> تاريخ الانشاء</th>
                        <th>من</th>
                        <th>الي</th>
                         <th>القيمه</th>
                          <th>رقم الفاتوره</th>
                          <th>طريقة التحويل </th>
                         
                        <th>الملاحظات</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">تحديث الحالة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="updateStatusForm">
          <input type="hidden" id="row_id" name="row_id">

          <div class="mb-3">
          
            <select id="client_status" name="client_status" class="form-select" style="width:100%">
              <option value="" disabled selected>اختر الحالة</option>
              <option value="pending">تحت الاجراء</option>
              <option value="approved">مقبول </option>
              <option value="refused">مرفوض</option>
              <!-- ممكن تضيف أي حالات تانية هنا -->
            </select>
          </div>
          <div class="mb-3">
   <textarea class="form-control" id="reason"> </textarea>

          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary"  id="saveStatusBtn">حفظ</button>
      </div>

    </div>
  </div>
</div>
@endsection
@section('js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#Modal').on('shown.bs.modal', function() {
                setTimeout(() => {
                    $('.client_id').select2({
                        dropdownParent: $('#Modal'),
                        placeholder: 'العملاء...',
                        allowClear: true,
                        ajax: {
                            url: '{{ route('admin.gettraders') }}',
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
                }, 1000);

            });

            $('#Modal').on('hidden.bs.modal', function() {
                $('.representative_id').select2('destroy'); // Use the specific class here too
            });
        });
    </script>
    <script>
        var columns = [{
                data: 'id',
                name: 'id'
            },
           {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'from_user',
                name: 'from_user'
            },
            {
                data: 'to_user',
                name: 'to_user'
            },
            {
                data: 'amount',
                name: 'amount'
            },
             {
                data: 'invoice_number',
                name: 'invoice_number'
            },
            {
                data: 'payMethod',
                name: 'payMethod'
            },
            {
                data: 'notes',
                name: 'notes'
            },
            
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ];
    </script>

    <script>

     $(document).on('click', '.openModalBtn', function(){
    let row_id = $(this).data('id');
    let status = $(this).data('status'); // القيمة الحالية
     let reason= $(this).data('reason'); // القيمة الحالية
    $('#row_id').val(row_id);
     $('#reason').val(reason);
    $('#client_status').val(status).trigger('change'); // تملأ الـ select
    $('#statusModal').modal('show');
});
    </script>


  <script>
        $(document).on('click', '.openModalBtn', function() {
   let client_id = $(this).data('id');
    let status = $(this).data('status'); // القيمة الحالية
     let reason= $(this).data('reason'); // القيمة الحالية
    $('#client_id').val(client_id);
     $('#reason').val(reason);
    $('#client_status').val(status).trigger('change'); // تملأ الـ select
    $('#statusModal').modal('show');
});

$('#saveStatusBtn').click(function () {

    let row_id = $('#row_id').val();
    let client_status = $('#client_status').val();
    let reason = $('#reason').val();

    $.ajax({
        url: "{{ route('admin.coupons-status.update_status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            row_id: row_id,
            status: client_status,
            reason: reason
        },

        success: function (res) {

            if (res.success) {

                $('#statusModal').modal('hide');

                // reload datatable
                $('#table')
                    .DataTable()
                    .ajax.reload(null, false);

                Swal.fire('تم التحديث!', res.message, 'success');

            } else {

                Swal.fire('خطأ!', res.message, 'error');

            }
        },

        error: function (err) {
            console.log(err);
            Swal.fire('خطأ!', 'حدث خطأ أثناء التحديث', 'error');
        }

    });

});


</script>
    @include('Admin.layouts.inc.ajax', ['url' => 'coupons-converts'])
@endsection
