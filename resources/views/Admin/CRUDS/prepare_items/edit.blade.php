@extends('Admin.layouts.inc.app')
@section('title')
    تعديل عملية بيع
@endsection
@section('css')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">تجهيز الاصناف</h5>


        </div>
        <div class="card-body">

            @include('Admin.CRUDS.prepare_items.parts.editForm')

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
        </script>


        <script>
            (function() {

                $("#client_id").select2({
                    placeholder: 'Channel...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getClients') }}',
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
            (function() {

                $("#productive_id").select2({
                    placeholder: 'Channel...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getProductiveTypeKham') }}',
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

                $("#company_id").select2({
                    placeholder: 'Channel...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.get-companies') }}',
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
                    url: "{{ route('admin.makeRowDetailsForSalesDetails') }}",

                    success: function(res) {

                        $('#details-container').append(res.html);
                        $("html,body").animate({
                            scrollTop: $(document).height()
                        }, 1000);


                        loadScript(res.id);
                        callTotal();


                    },
                    error: function(data) {
                        // location.reload();
                    }
                });


            })
        </script>



        <script>
            function loadScript(id) {
                $(`#productive_id-${id}`).select2({
                    placeholder: 'searching For Supplier...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.getProductiveTypeKham') }}',
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

                $(`#company_id-${id}`).select2({
                    placeholder: 'searching For Supplier...',
                    // width: '350px',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('admin.get-companies') }}',
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
            $(document).on('change', '.changeKhamId', function() {

                var rowId = $(this).attr('data-id');
                var id = $(this).val();
                var route = "{{ route('admin.getProductiveDetails', ':id') }}";
                route = route.replace(':id', id);

                $.ajax({
                    type: 'GET',
                    url: route,

                    success: function(res) {


                        $(`#unit-${rowId}`).val(res.unit);
                        $(`#productive_code-${rowId}`).val(res.code);
                        $(`#productive_sale_price-${rowId}`).val(res.productive_sale_price);
                        callTotal();

                    },
                    error: function(data) {
                        // location.reload();
                    }
                });

            })
        </script>
        <script>
            function callTotal() {
                var amounts = document.getElementsByName('amount[]');
                var prices = document.getElementsByName('productive_sale_price[]');

                var total = 0;
                var subTotal = 0;
                for (var i = 0; i < amounts.length; i++) {
                    subTotal = 1;
                    var amount = amounts[i];
                    var price = prices[i];
                    subTotal = amount.value * price.value;
                    var rowId = amount.getAttribute('data-id');
                    $(`#total-${rowId}`).val(subTotal);
                    total = total + subTotal;
                }


                $('#total_productive_sale_price').text(total);
            }
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
                                // $('#form')[0].reset();

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
            @foreach (\App\Models\SalesDetails::where('sales_id', $row->id)->get() as $key => $pivot)

                loadScript({{ $key }})
            @endforeach
        </script>
        <script>
            $(document).ready(function() {
                $(document).on('change', '.is-prepared-toggle', function() {
                    var isPrepared = $(this).prop('checked') ? 1 : 0;
                    var rowId = $(this).data('id');
                    
                    let url = '{{route('update.prepare-status')}}';

                    $.ajax({
                        url: url, // Replace with your actual update URL
                        method: 'POST',
                        data: {
                            id: rowId,
                            is_prepared: isPrepared
                        },
                        success: function(response) {
                                                        
                        },
                        error: function(xhr, status, error) {
                            console.error('Error updating is_prepared status:', error);
                            alert('Failed to update status. Please try again.');
                            // Revert the switch if the update failed
                            $(this).prop('checked', !isPrepared);
                        }
                    });
                });
            });
        </script>
    @endsection
