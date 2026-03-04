@extends('Admin.layouts.inc.app')
@section('title')
    كشف حساب مورد
@endsection
@section('css')
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0 flex-grow-1">كشف حساب</h5>
                    <div class="row my-4 g-4">
                        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
                            <!--begin::Label-->
                            <label for="supplier_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required mr-1"> المورد</span>
                            </label>
                            <select id='supplier_id' name="supplier_id" style='width: 200px;'>
                            </select>
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row col-sm-3 mt-5">
                            <button form="form" class="btn btn-primary" id = "filter">
                                <span class="indicator-label">بحث</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="table-container">

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@endsection
@section('js')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        (function() {

            $("#supplier_id").select2({
                placeholder: 'Channel...',
                // width: '350px',
                allowClear: true,
                ajax: {
                    url: '{{ route('admin.getSupplier') }}',
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
        })();
    </script>
    <script>
        $(document).on('click', '#filter', function() {
            var supplier_id = $('#supplier_id').val();
            console.log(supplier_id);

            if (!supplier_id) {
                alert('اختر المورد أولاً');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "{{ route('admin.supplierAccountStatements') }}",
                data: {
                    supplier_id: supplier_id,
                },
                success: function(res) {

                    $('#table-container').html(res.html);


                },
                error: function(data) {
                    // location.reload();
                }
            });

        })
    </script>
@endsection
