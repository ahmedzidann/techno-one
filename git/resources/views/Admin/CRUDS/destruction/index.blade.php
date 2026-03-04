@extends('Admin.layouts.inc.app')
@section('title')
    الاهلاك
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">الاهلاك</h5>

            <div>
                <a href="{{route('destruction.create')}}" class="btn btn-primary">اضافة  الاهلاك</a>
            </div>

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                   style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>تاريخ الاهلاك</th>
                    <th> المخزن</th>
                    <th> التفاصيل</th>
                    <th> تاريخ الانشاء</th>
                    <th>العمليات</th>
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
                    <h2><span id="operationType"></span> الاهلاك </h2>
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

@endsection
@section('js')
    <script>
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'destruction_date', name: 'destruction_date'},
            {data: 'storage.title', name: 'storage.title'},
            {data: 'details', name: 'details'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
    </script>
    @include('Admin.layouts.inc.ajax',['url'=>'destruction'])

    <script>
        $(document).on('click','.showDetails',function (){
            var id=$(this).attr('data-id');
            var route="{{route('admin.getDestructionDetails',':id')}}";
            route=route.replace(':id',id);
            $('#form-load').html(loader_form)
            $('#operationType').text('{{trans('عرض')}}');

            $('#Modal').modal('show')

            setTimeout(function (){
                $('#form-load').load(route)
            },1000)
        })
    </script>

    <script>
        $(document).on('click','.editBtn-p',function (){
            var  id = $(this).data('id');


            var url = "{{route("destruction.edit",':id')}}";
            url = url.replace(':id',id)

            window.location.href=url;

        })
    </script>



@endsection
