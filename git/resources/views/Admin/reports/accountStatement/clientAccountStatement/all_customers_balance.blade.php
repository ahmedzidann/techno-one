@extends('Admin.layouts.inc.app')

@section('title','ارصدة العملاء')

@section('css')



@endsection

@section('content')

<div class="card">
<div class="card-header">
<h5>أرصدة العملاء بالكوبونات</h5>
</div>

<div class="card-body">

<table id="customersBalanceTable" class="table table-bordered w-100">
<thead>
<tr>
<th>#</th>
<th>اسم العميل</th>
<th>كود العميل </th>
<th>رقم التليفون </th>
<th>رصيد أول مدة</th>
<th>الإضافة</th>
<th>الخصم</th>
<th>الرصيد النهائي</th>
</tr>
</thead>
</table>

</div>
</div>


@endsection

@section('js')

<script src="{{ URL::asset('assets_new/datatable/feather.min.js') }}"></script>
<script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>




<script>

$(function(){

$('#customersBalanceTable').DataTable({

 processing: true,
            // pageLength: 50,
            paging: true,
            dom: 'Bfrltip',

            bLengthChange: true,
            serverSide: true,

dom:'Bfrtip',


ajax:"{{ route('admin.customers_balances') }}",

columns:[

{
data:null,
searchable:false,
orderable:false,
render:function(data,type,row,meta){
return meta.row + meta.settings._iDisplayStart + 1;
}
},

{data:'name',name:'clients.name'},
{data:'code',name:'clients.code'},
{data:'phone',name:'clients.phone'},
{data:'previous_balance'},
{data:'increase'},
{data:'decrease'},
{data:'final_balance'}

]

});

});

</script>


@endsection
