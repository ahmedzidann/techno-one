@extends('Admin.layouts.inc.app')
@section('title')
    ربط المناديب بالعملاء
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="master_checkbox"> </th>
                        <th>الاسم</th>
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
            <div class="row">
                <div class="d-flex flex-column mb-7 fv-row col-sm-3 ml-2">
                    <!--begin::Label-->
                    <label for="representative_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span class="required mr-1"> اختر المندوب</span>
                    </label>

                    <select name="representative_id" class="select2 representative_id" id='representative_id'
                        style='width: 200px;'>
                    </select>

                </div>
                <div class="col-4 mt-4">
                    <button form="form" id="submit" class="btn btn-primary">
                        <span class="indicator-label">اتمام</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ URL::asset('assets_new/datatable/feather.min.js') }}"></script>
    <script src="{{ URL::asset('assets_new/datatable/datatables.min.js') }}"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#master_checkbox').on('click', function() {
                $('.client_id').prop('checked', $(this).is(':checked'));

            });
            $(document).on('click', '.client_id', function() {
                $('#master_checkbox').prop('checked', $('.client_id:checked').length === $('.client_id')
                    .length);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.representative_id').select2({
                placeholder: 'المندوب...',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.getRepresentatives') }}',
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
        });
    </script>
    <script>
        var columns = [{
                data: 'checkbox',
                name: 'checkbox',
                orderable: false,
                searchable: false,
            },
            {
                data: 'name',
                name: 'name',
                orderable: false,
                searchable: false,
            },
            {
                data: 'code',
                name: 'code',
                orderable: false,
                searchable: false,
            },
            {
                data: 'phone',
                name: 'phone',
                orderable: false,
                searchable: false,
            },
            {
                data: 'governorate.title',
                name: 'governorate.title',
                orderable: false,
                searchable: false,
            },
            {
                data: 'city.title',
                name: 'city.title',
                orderable: false,
                searchable: false,
            },
            {
                data: 'previous_indebtedness',
                name: 'previous_indebtedness',
                orderable: false,
                searchable: false,
            },
            {
                data: 'address',
                name: 'address',
                orderable: false,
                searchable: false,
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
        $(function() {
            $("#table").DataTable({
                processing: true,
                // pageLength: 50,
                paging: true,
                dom: 'Bfrltip',

                bLengthChange: true,
                serverSide: true,
                url: "{{ route('representative-clients.index') }}",
                columns: columns,
                // order: [
                //     [0, "asc"]
                // ],
                "language": <?php echo json_encode(datatable_lang()); ?>,
                // "language": {
                //     paginate: {
                //         previous: "<i class='simple-icon-arrow-left'></i>",
                //         next: "<i class='simple-icon-arrow-right'></i>"
                //     },
                //     "sProcessing": "جاري التحميل ..",
                //     "sLengthMenu": "اظهار _MENU_ سجل",
                //     "sZeroRecords": "لا يوجد نتائج",
                //     "sInfo": "اظهار _START_ الى  _END_ من _TOTAL_ سجل",
                //     "sInfoEmpty": "لا نتائج",
                //     "sInfoFiltered": "للبحث",
                //     "sSearch": "بحث :    ",
                //     "oPaginate": {
                //         "sPrevious": "السابق",
                //         "sNext": "التالي",
                //     }
                // },
                // buttons: [
                //     'colvis',
                //     'excel',
                //     'print',
                //     'copy',
                //     'csv',
                //     // 'pdf'
                // ],

                searching: true,
                destroy: true,
                info: false,


            });

        });
    </script>

    <script>
        $(document).on('change', '#governorate_id', function() {
            var from_id = $(this).val();

            var route = "{{ route('admin.getCitiesForGovernorate', ':id') }}";
            route = route.replace(':id', from_id);

            setTimeout(function() {
                $('#city_id').load(route);
            }, 1000)
        })
    </script>
    <script>
        $(document).on('click', '#submit', function() {
            // Collect checked values
            var checkedValues = [];
            $('.client_id:checked').each(function() {
                checkedValues.push($(this).val());
            });

            // Get the selected value of the representative_id dropdown
            var representativeId = $('#representative_id').val();

            // Check if there are any checked values and if representativeId is selected
            if (checkedValues.length === 0) {
                alert('من فضلك اختار عميل واحد عالأقل.');
            } else if (!representativeId) {
                alert('من فضلك اختار المندوب.');
            } else {
                $.ajax({
                    url: "{{ route('add-clients-to-representative.create') }}",
                    type: 'POST',
                    data: {
                        client_ids: checkedValues,
                        representative_id: representativeId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // For CSRF token in Laravel
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                        console.log(xhr, status, error);
                    }
                });
            }
        });
    </script>
@endsection
