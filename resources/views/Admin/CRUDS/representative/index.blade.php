@extends('Admin.layouts.inc.app')
@section('title')
    المناديب
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">المناديب</h5>

            {{--            @can('اضافة مستخدمين') --}}
            <div>
                <button id="addBtn" class="btn btn-primary">اضافة مندوب</button>
            </div>
            {{--            @endcan --}}

        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المندوب</th>
                        <th>اسم المستخدم</th>
                        <th>رقم التليفون</th>
                        <th>المخزن</th>
                        <th>الفرع</th>
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
                    <h2><span id="operationType"></span> مندوب </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <button class="btn btn-sm btn-icon btn-active-color-primary" type="button" data-bs-dismiss="modal"
                        aria-label="Close">
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
        var columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'full_name',
                name: 'full_name'
            },
            {
                data: 'user_name',
                name: 'user_name'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'storage.title',
                name: 'storage.title'
            },
            {
                data: 'branch.title',
                name: 'branch.title'
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
    @include('Admin.layouts.inc.ajax', ['url' => 'representatives'])
    <script>
        $(document).on('click', '.details', function() {
            var id = $(this).data('id');
            $('#operationType').text('عملاء ');
            $('#form-load').html(loader_form)
            $('#Modal').modal('show')

            var url = "{{ route('representatives.details', ':id') }}";
            url = url.replace(':id', id)

            setTimeout(function() {
                $('#form-load').load(url)
            }, 1000)

        })
    </script>
    <script>
        $(document).on('click', '.delete-client', function() {

            swal.fire({
                title: "{{ trans('admin.submit delete') }}",
                text: "{{ trans('admin.delete text') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "{{ trans('admin.submit') }}",
                cancelButtonText: "{{ trans('admin.cancel') }}",
                okButtonText: "{{ trans('admin.submit') }}",
                closeOnConfirm: false
            }).then((result) => {
                if (!result.isConfirmed) {
                    return true;
                }
                let clientId = $(this).data('client-id');
                let representativeId = $(this).data('representative-id');
                console.log(clientId);
                console.log(representativeId);

                var url = "{{ route('representative-clients.delete') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        client_id: clientId,
                        representative_id: representativeId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // For CSRF token in Laravel
                    },
                    beforeSend: function() {
                        $('.loader-ajax').show()

                    },
                    success: function(data) {

                        window.setTimeout(function() {
                            $('.loader-ajax').hide()
                            if (data.code == 200) {
                                toastr.success(data.message)
                                location.reload();
                                $('#table').DataTable().ajax.reload(null, false);
                            } else {
                                toastr.error('{{ trans('admin.error') }}')
                            }

                        }, 1000);
                    },
                    error: function(data) {

                        if (data.status === 500) {
                            toastr.error('{{ trans('admin.error') }}')
                        }


                        if (data.status === 422) {
                            var errors = $.parseJSON(data.responseText);

                            $.each(errors, function(key, value) {
                                if ($.isPlainObject(value)) {
                                    $.each(value, function(key, value) {
                                        toastr.error(value)
                                    });

                                } else {

                                }
                            });
                        }
                    }

                });
            });
        });
    </script>
@endsection
