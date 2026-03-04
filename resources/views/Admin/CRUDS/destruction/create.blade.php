@extends('Admin.layouts.inc.app')
@section('title')
    اضافة اهلاك
@endsection
@section('css')
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">عملية اهلاك</h5>


        </div>
        <div class="card-body">

            @include('Admin.CRUDS.destruction.parts.form')

        </div>
    @endsection

    @section('js')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            (function() {

                $("#storage_id").select2({
                    placeholder: 'Channel...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getStorages') }}',
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

            (function() {

                $("#productive_id").select2({
                    placeholder: 'Channel...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getAllProductive') }}',
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
            $(document).on('click', '.delete-sup', function(e) {
                e.preventDefault();
                var rowId = $(this).attr('data-id');
                $(`#tr-${rowId}`).remove();
            })
        </script>


        <script>
            $(document).on('click', '#addNewDetails', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.makeRowDetailsForDestructionDetails') }}",

                    success: function(res) {

                        $('#details-container').append(res.html);
                        $("html,body").animate({
                            scrollTop: $(document).height()
                        }, 1000);


                        loadScript(res.id);


                    },
                    error: function(data) {
                        // location.reload();
                    }
                });


            })
        </script>


        <script>
            loadScript(1);

            function loadScript(id) {
                $(`#productive_id-${id}`).select2({
                    placeholder: 'searching For Supplier...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getAllProductive') }}',
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

        <script>
            $(document).on('change', '.getDestructionPrice', function() {
                var productive_id = 0;
                var rowId = $(this).attr('data-id');
                if (rowId == 1) {
                    productive_id = $(`#productive_id`).val();
                } else {
                    productive_id = $(`#productive_id-${rowId}`).val();

                }
                var type = $(`#type-${rowId}`).val();





                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.getDestructionPrice') }}",
                    data: {
                        productive_id: productive_id,
                        type: type,
                    },

                    success: function(res) {


                        $(`#price-${rowId}`).val(res.price);
                        total(rowId);

                    },
                    error: function(data) {
                        // location.reload();
                    }
                });

            })
        </script>
        <script>
            $(document).on('submit', "#form", function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                var url = $('#form').attr('action');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {


                        $('#submit').html('<span class="spinner-border spinner-border-sm mr-2" ' +
                            ' ></span> <span style="margin-left: 4px;">{{ trans('admin.working') }}</span>'
                        ).attr('disabled', true);
                    },
                    complete: function() {},
                    success: function(data) {

                        window.setTimeout(function() {
                            $('#submit').html('{{ trans('admin.submit') }}').attr('disabled',
                                false);

                            if (data.code == 200) {
                                toastr.success(data.message)
                                $('#form')[0].reset();

                            } else {
                                toastr.error(data.message)
                            }
                        }, 1000);


                    },
                    error: function(data) {
                        $('#submit').html('{{ trans('admin.submit') }}').attr('disabled', false);
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
                        if (data.status == 421) {
                            toastr.error(data.message)
                        }

                    }, //end error method

                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        </script>

        <script>
            $(document).on('change keyup', '.changeTotal', function() {
                var id = $(this).attr('data-id');
                total(id);
            })

            function total(id) {
                var price = $(`#productive_buy_price-${id}`).val();
                var amount = $(`#amount-${id}`).val();
                var total = price * amount;
                $(`#total-${id}`).val(total);
            }
        </script>
        <script>
            function getPrice(id) {

                let PRODUCT_ID = $(`#productive_id-${id}`).val();
                let BATCH_NUMBER = $(`#batch_number-${id}`).val();

                var route = "{{ route('admin.getProductPrice', ':id') }}";
                route = route.replace(':id', PRODUCT_ID);
                $.ajax({
                    type: 'GET',
                    url: route,
                    data: {
                        id: PRODUCT_ID,
                        batch_number: BATCH_NUMBER,
                    },
                    success: function(res) {
                        console.log(res);
                        $(`#productive_sale_price-${id}`).val(res.sell_price);
                        $(`#productive_buy_price-${id}`).val(res.buy_price);
                        total(id)

                    },
                    error: function(data) {
                        toastr.error('هناك خطأ ما!');
                    }
                });
            }
            $(document).on('change', '.changeKhamId', function() {

                var rowId = $(this).attr('data-id');
                var id = $(this).val();
                var route = "{{ route('admin.getProductiveDetails', ':id') }}";
                route = route.replace(':id', id);
                const selectElement = document.getElementById('batch_number-' + rowId);

                $.ajax({
                    type: 'GET',
                    url: route,

                    success: function(res) {

                        $(`#productive_code-${rowId}`).val(res.code);
                        // $(`#productive_sale_price-${rowId}`).val(res.productive_sale_price);
                        $(`#company_id-${rowId}`).val(res.productive.company_id);
                        let options = res.productive.batches;
                        selectElement.innerHTML = '';
                        if (options && options.length > 0) {
                            options.forEach(option => {
                                const opt = document.createElement('option');
                                opt.value = option.batch_number;
                                opt.textContent = option.batch_number;
                                selectElement.appendChild(opt);
                            });
                        } else {
                            const opt = document.createElement('option');
                            opt.textContent = 'لايوجد رقم تشغيلة';
                            opt.value = null;
                            selectElement.appendChild(opt);
                        }
                        getPrice(rowId);

                        // callTotal();
                        total(rowId)
                    },
                    error: function(data) {
                        // location.reload();
                    }
                });

            })
        </script>
    @endsection
