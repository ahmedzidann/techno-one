@extends('Admin.layouts.inc.app')
@section('title')
    رصيد اول عينة
@endsection
@section('css')
@endsection
@section('content')


    <div class="row mb-3">
        <div class="col-md-2 ">
            <label for="category_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  القسم    </span>

            </label>
              <select class="form-control" id="category_id">
                  <option selected disabled>اختر القسم</option>
                  @foreach($categories as $category)
                  <option @isset($request['category_id'])  @if($request['category_id']==$category->id) selected  @endif  @endisset value="{{$category->id}}">{{$category->title}}</option>
                  @endforeach
              </select>

        </div>
        <div class="col-md-2">
           <button class="btn btn-outline-success mt-4" id="filtering">بحث</button>
        </div>
    </div>





    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">رصيد اول عينة</h5>



        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                   style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th> الكود</th>
                    <th> الوحدة</th>
                    <th> التصنيف</th>
                    <th> النوع</th>
                    <th> سعر الشراء </th>
                    {{-- <th> سعر شراء المجموعة</th> --}}
                    <th> سعر البيع </th>
                    {{-- <th> سعر بيع المجموعة</th> --}}
                    {{-- <th> عدد الوحدات داخل القطعة</th> --}}
                    <th>الرصيد</th>
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
                    <h2><span id="operationType"></span> صنف </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <button class="btn btn-sm btn-icon btn-active-color-primary" type="button" data-bs-dismiss="modal" aria-label="Close">
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
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'code', name: 'code'},
            {data: 'unit.title', name: 'unit.title'},
            {data: 'category.title', name: 'category.title'},
            {data: 'product_type', name: 'product_type'},
            {data: 'one_buy_price', name: 'one_buy_price'},
            // {data: 'packet_buy_price', name: 'packet_buy_price'},
            {data: 'one_sell_price', name: 'one_sell_price'},
            // {data: 'packet_sell_price', name: 'packet_sell_price'},
            // {data: 'num_pieces_in_package', name: 'num_pieces_in_package'},
            {data: 'amount', name: 'amount'},
        ];
    </script>
    @include('Admin.layouts.inc.ajax',['url'=>'rasied_ayni'])


    <script>
        $(document).on('click','.showAmount',function (){

            var id=$(this).attr('data-id');
            $('#form-load').html(loader_form)
            $('#operationType').text('عرض رصيد ');

            $('#Modal').modal('show')

            var url="{{route('admin.rasied_ayni_for_productive',':id')}}";
            url=url.replace(':id',id);

            setTimeout(function (){
                $('#form-load').load(url)
            },1000)
        })
    </script>

    <script>
        $(document).on('click','#filtering',function (){
            var category_id=$('#category_id').val();
            var route="{{route('rasied_ayni.index')}}?category_id="+category_id;
            if(category_id != null){

                window.location.href=route;
            }
        })
    </script>

    <script>

        $(document).on('change','#branch_id',function (){
            var branch_id=$(this).val();
            var  routing="{{route('admin.getStorageForBranch',':id')}}";
            routing=routing.replace(':id',branch_id);

            setTimeout(function (){
                $('#storage_id').load(routing)
            },1000)

        })

    </script>


    <script>
        $(document).on('submit',"#credit-form",function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            var url = $(this).attr('action');
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                beforeSend: function () {


                    $('#credit-submit').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                        ' ></span> <span style="margin-left: 4px;">{{trans('admin.working')}}</span>').attr('disabled', true);

                },
                complete: function () {
                },
                success: function (data) {

                    window.setTimeout(function () {

                        $('#credit-submit').html('{{trans('admin.submit')}}').attr('disabled', false);
                        if (data.code == 200) {
                            toastr.success(data.message)
                            $('#table').DataTable().ajax.reload(null, false);
                            $('#credit-form')[0].reset();

                            var table = $('#productive_table').DataTable();
                            table.destroy();

                            var route="{{route('admin.gitCreditForProductive',':id')}}";
                            route=route.replace(':id',data.id);

                            setTimeout(function (){
                                $('#creditTableBody').load(route)
                            },1000)

                            $("#productive_table").DataTable({
                                processing: true,
                                // pageLength: 50,
                                paging: true,
                                dom: 'Bfrltip',

                                bLengthChange: true,


                                "language":<?php echo json_encode(datatable_lang());?>,

                                searching: true,
                                destroy: true,
                                info: false,


                            });




                        }else {
                            toastr.error(data.message)
                        }
                    }, 1000);



                },
                error: function (data) {
                    $('#credit-submit').html('{{trans('admin.submit')}}').attr('disabled', false);
                    if (data.status === 500) {
                        toastr.error('{{trans('admin.error')}}')
                    }
                    if (data.status === 422) {
                        var errors = $.parseJSON(data.responseText);

                        $.each(errors, function (key, value) {
                            if ($.isPlainObject(value)) {
                                $.each(value, function (key, value) {
                                    toastr.error(value)
                                });

                            } else {

                            }
                        });
                    }
                    if (data.status == 421){
                        toastr.error(data.message)
                    }

                },//end error method

                cache: false,
                contentType: false,
                processData: false
            });
        });

    </script>

    <script>
       $(document).on('click','.deleteCredit',function (){

           var id=$(this).attr('data-id');
           swal.fire({
               title: "{{trans('admin.submit delete')}}",
               text: "{{trans('admin.delete text')}}",
               icon: "warning",
               showCancelButton: true,
               confirmButtonColor: "#DD6B55",
               confirmButtonText: "{{trans('admin.submit')}}",
               cancelButtonText: "{{trans('admin.cancel')}}",
               okButtonText: "{{trans('admin.submit')}}",
               closeOnConfirm: false
           }).then((result) => {
               if (!result.isConfirmed){
                   return true;
               }


               var url = '{{ route("rasied_ayni.destroy",":id") }}';
               url = url.replace(':id',id)
               $.ajax({
                   url: url,
                   type: 'DELETE',
                   beforeSend: function(){
                       $('.loader-ajax').show()

                   },
                   success: function (data) {

                       window.setTimeout(function() {
                           $('.loader-ajax').hide()
                           if (data.code == 200){
                               toastr.success(data.message)
                               $('#table').DataTable().ajax.reload(null, false);
                               $(`#tr-${id}`).remove();
                           }else {
                               toastr.error('{{trans('admin.error')}}')
                           }

                       }, 1000);
                   }, error: function (data) {

                       if (data.status === 500) {
                           toastr.error('{{trans('admin.error')}}')
                       }


                       if (data.status === 422) {
                           var errors = $.parseJSON(data.responseText);

                           $.each(errors, function (key, value) {
                               if ($.isPlainObject(value)) {
                                   $.each(value, function (key, value) {
                                       toastr.error(value)
                                   });

                               } else {

                               }
                           });
                       }
                   }

               });
           });
       })
    </script>

@endsection
