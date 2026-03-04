@extends('Admin.layouts.inc.app')
@section('title')
    الادوار
@endsection
@section('css')
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .group {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .group-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        h2 {
            color: #333;
            margin: 0;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 8px;
        }

        label {
            display: block;
            padding: 8px;
            background-color: #f9f9f9;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        label:hover {
            background-color: #e9e9e9;
        }

        input[type="checkbox"] {
            margin-left: 8px;
        }

        .group-checkbox {
            transform: scale(1.5);
            margin-right: 10px;
        }

        .group.checked {
            background-color: #e6f7ff;
        }

        .group.checked label {
            background-color: #d1e8ff;
        }
    </style>
@endsection
@section('breadCramp')
    breadCramp
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="col-lg-12 mt-2">
                            {{--                            @can('اضافة الدور') --}}
                            <div class="pull-right">
                                <button id="addBtn" class="btn btn-primary waves-effect waves-light btndd">اضافة
                                    دور</button>
                            </div>
                            {{--                            @endcan --}}
                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="table-responsive1 mt-2">
                        <table id="table" class="table table-striped table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>تاريخ التعين</th>
                                    <th>العمليات</th>

                                </tr>
                            </thead>

                            <tbody>

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered modal-lg mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content" id="modalContent">
                <!--begin::Modal header-->
                <div class="modal-header">
                    <!--begin::Modal title-->
                    <h2><span id="operationType"></span> دور </h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-color-primary" style="cursor: pointer"
                        data-bs-dismiss="modal" aria-label="Close">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--begin::Modal body-->
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7" id="form-load">

                </div>
                <!--end::Modal body-->
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="reset" data-bs-dismiss="modal" aria-label="Close" class="btn btn-light me-3">
                            الغاء
                        </button>
                        <button form="form" type="submit" id="submit" class="btn btn-primary">
                            <span class="indicator-label"> اتمام </span>
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
        function toggleGroup(checkbox, groupId) {
            const group = document.getElementById(groupId);
            const checkboxes = group.querySelectorAll('ul input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            group.classList.toggle('checked', checkbox.checked);
        }
    </script>
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
    @include('Admin.layouts.inc.ajax', ['url' => 'roles'])
@endsection
