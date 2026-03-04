@extends('Admin.layouts.inc.app')
@section('title')
    تكوين الاصناف
@endsection
@section('css')
    <style>
        .select2-container{
            z-index:100000;
        }
    </style>

@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">تكوين الاصناف</h5>

                <div>
                    <button id="addBtn" class="btn btn-primary">اضافة تكوين لصنف</button>
                </div>

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                   style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th> المنتج</th>
                    <th>  الخامات</th>
                    <th>العمليات</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <input type="hidden" id="tr_clickable_id">

    <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType"></span> تكوين </h2>
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


    <div class="modal fade" id="Modal-sub" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent-sub">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType-sub"></span> المنتج الخام </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <button class="btn btn-sm btn-icon btn-active-color-primary" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </button>
                    <!--end::Close-->
                </div>
                <!--begin::Modal body-->
                <div class="modal-body py-4" id="form-load-sub">

                </div>
                <!--end::Modal body-->
                <div class="modal-footer">
                    <div class="text-center">
                        <button id="closeSupModal" type="reset" data-bs-dismiss="modal" aria-label="Close" class="btn btn-light me-2">
                            الغاء
                        </button>
{{--                        <button form="form" type="submit" id="submit" class="btn btn-primary">--}}
{{--                            <span class="indicator-label">اتمام</span>--}}
{{--                        </button>--}}
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
            {data: 'install_date', name: 'install_date'},
            {data: 'productive.name', name: 'productive.name'},
            {data: 'details', name: 'details'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('Admin.layouts.inc.ajax',['url'=>'itemInstallations'])

    <script>
        $(document).on('click','.delete-sup',function (e){
            e.preventDefault();
            var id=$(this).attr('data-id');
            $(`#tr-${id}`).remove();

        })
    </script>
    <script>
        $(document).on('click','#addNewDetails',function (e){
            e.preventDefault();
            $.ajax({
                type: 'GET',
                url: "{{route('admin.makeRowDetailsForItemInstallations')}}",

                success: function (res) {

                    $('#details-container').append(res.html);
                    $("Modal").animate({ scrollTop: $(document).height() }, 1000);


                    loadScript(res.id);





    },
                error: function (data) {
                    // location.reload();
                }
            });


        })
    </script>

    <script>
        $(document).on('click','.changeProductive',function (e){
            e.preventDefault();
            var id=$(this).attr('data-id');
            $('#Modal').modal('hide')
            $('#tr_clickable_id').val(id)



            $('#form-load-sub').html(loader_form)
            $('#operationType-sub').text('{{trans('admin.add')}}');

            $('#Modal-sub').modal('show')

            setTimeout(function (){
                $('#form-load-sub').load("{{route("admin.getSubProductive")}}")
            },1000)


        })
    </script>

    <script>
        $(document).on('change','#sub_productive_select',function (){
            var productive_id=$(this).val();
               var url="{{route('admin.getProductiveDetails',':id')}}";
               url=url.replace(':id',productive_id);


            $.ajax({
                type: 'GET',
                url: url,

                success: function (res) {
                    $('#Modal-sub').modal('hide');
                    $('#Modal').modal('show');
                    var rowId=$('#tr_clickable_id').val();

                    var inputs = document.getElementsByName('productive_id[]');
                    var test=0;
                    for (var i = 0; i < inputs.length; i++) {
                        var a = inputs[i];
                         if(a.value==res.productive_id){
                             test=test+1;

                         }
                    }

                    if(test==0) {
                        $(`#unit-${rowId}`).val(res.unit);
                        $(`#name-${rowId}`).val(res.name);
                        $(`#code-${rowId}`).val(res.code);
                        $(`#sub_productive_id-${rowId}`).val(res.productive_id)
                    }
                    else {
                        toastr.error('تلك المنتج موجود مسبقا')

                    }

                },
                error: function (data) {
                    // location.reload();
                }
            });
        })
    </script>

    <script>
        $(document).on('click','#closeSupModal',function (){
            $('#Modal').modal('show');

        })
    </script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).on('change','.changeKhamId',function (e){




           var rowId=$(this).attr('data-id');
           var id=$(this).val();
           var route="{{route('admin.getProductiveDetails',':id')}}";
           route=route.replace(':id',id);

            $.ajax({
                type: 'GET',
                url: route,

                success: function (res) {


                        $(`#unit-${rowId}`).val(res.unit);
                        $(`#code-${rowId}`).val(res.code);


                },
                error: function (data) {
                    // location.reload();
                }
            });

        })
    </script>

    <script>
        function loadScript(id){
            $(`#channel_id-${id}`).select2({
                placeholder: 'searching For Supplier...',
                // width: '350px',
                allowClear: true,
                ajax: {
                    url: '{{route('admin.getProductiveTypeKham')}}',
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

        }
    </script>

@endsection
