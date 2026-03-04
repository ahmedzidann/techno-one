@extends('Admin.layouts.inc.app')
@section('title')
    العملاء
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">العملاء</h5>

         

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                       <th>نوع العميل</th>
                       <th>طريقة التسجيل </th>
                        <th>الكود</th>
                        <th>الهاتف</th>
                        <th>المحافظة</th>
                        <th>المدينة</th>
                        <th>المديونية السابقة </th>
                        <th>العنوان</th>
                        <th> تاريخ الانشاء</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

   <!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">تحديث الحالة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="updateStatusForm">
          <input type="hidden" id="client_id" name="client_id">

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
        var columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'client_type',
                name: 'client_type'
            },
             {
                data: 'register_type',
                name: 'register_type'
            },
            
            
            {
                data: 'code',
                name: 'code'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'governorate.title',
                name: 'governorate.title'
            },
            {
                data: 'city.title',
                name: 'city.title'
            },
            {
                data: 'previous_indebtedness',
                name: 'previous_indebtedness'
            },
            {
                data: 'address',
                name: 'address'
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
     <script>

     $(document).on('click', '.openModalBtn', function(){
    let client_id = $(this).data('id');
    let status = $(this).data('status'); // القيمة الحالية
     let reason= $(this).data('reason'); // القيمة الحالية
    $('#client_id').val(client_id);
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

$('#saveStatusBtn').click(function() {
    let client_id = $('#client_id').val();
    let client_status = $('#client_status').val();
    let reason = $('#reason').val();

    $.ajax({
        url: "{{ route('admin.clients.update_status') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            client_id: client_id,
            status: client_status,
            reason: reason
        },
        success: function(res) {
            if(res.success){
                $('#statusModal').modal('hide');
                $('#customersBalanceTable').DataTable().ajax.reload(); // تحديث الجدول
                Swal.fire('تم التحديث!', res.message, 'success');
            } else {
                Swal.fire('خطأ!', res.message, 'error');
            }
        },
        error: function(err){
            console.log(err);
            Swal.fire('خطأ!', 'حدث خطأ أثناء التحديث', 'error');
        }
    });
});

</script>





    @include('Admin.layouts.inc.ajax', ['url' => 'clients'])

   
@endsection
