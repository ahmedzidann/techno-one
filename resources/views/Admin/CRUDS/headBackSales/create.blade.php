@extends('Admin.layouts.inc.app')
@section('title')
    اضافة عملية مرتجع مبيعات
@endsection
@section('css')
    <style>
        /* For all browsers */
        input[type="number"] {
            -moz-appearance: textfield;
            /* Firefox */
            -webkit-appearance: none;
            /* Chrome, Safari, and Edge */
            appearance: none;
            /* Standard */
        }

        /* Optional: Remove extra margin/padding in Firefox */
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endsection
@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">عملية مرتجع مبيعات</h5>


        </div>
        <div class="card-body">

            @include('Admin.CRUDS.headBackSales.parts.form')

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

            $(document).on('change', '#client_id', function() {
                let client = $('#client_id').val(); // Use the correct selector with `#` for ID
                if (client) {
                    let url = '{{ route('admin.getSalesForClient', ['client_id' => ':id']) }}'.replace(':id', client);
                    $("#sale_number_id").select2({
                        placeholder: 'Channel...',
                        // width: '350px',  
                        allowClear: true,
                        ajax: {
                            url: url,
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
            });

            $(document).on('change', '#sale_number_id', function(e) {
                e.preventDefault();
                let sale = $('#sale_number_id').val();
                if (sale) {
                    let url = '{{ route('admin.head-back-sales.invoice-details', ['sale_number_id' => ':id']) }}'
                        .replace(':id', sale);
                    $.ajax({
                        type: 'GET',
                        url: url,

                        success: function(res) {
                            $('#fatorah').empty();
                            $('#fatorah').html(res.html)
                        },
                        error: function(data) {
                            toastr.error(data.error)
                        }
                    });


                }
            })
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
                var discounts = document.getElementsByName('discount_percentage[]');
                let checkedData = Array.from(document.querySelectorAll('input[name^="check_data["]:checked'))
                    .map(checkbox => {
                        let match = checkbox.name.match(/\[(\d+)\]/);
                        return match ? match[1] : null; // Return the key
                    })
                    .filter(key => key !== null);

                console.log(checkedData);

                var total = 0;
                var subTotal = 0;
                for (var i = 0; i < amounts.length; i++) {
                    if (checkedData.includes(String(i))) {
                        subTotal = 1;
                        var amount = amounts[i];
                        var price = prices[i];
                        var discount = discounts[i];
                        subTotal = amount.value * price.value - (amount.value * price.value * discount.value / 100);
                        var rowId = amount.getAttribute('data-id');
                        $(`#total-${rowId}`).val(subTotal);
                        total = total + subTotal;
                    }
                }

                $('#total_productive_sale_price').text(total);
                totalAfterDiscount();
            }

            function totalAfterDiscount() {
                let total = parseFloat($('#total_productive_sale_price').text());
                $('#total_after_discount').val(total - (total * $('#total_discount').val() / 100))
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
            const form = document.getElementById('form');

            form.addEventListener('input', (event) => {
                const target = event.target; // The input that triggered the event
                if (target.type === 'number') {
                    const max = parseInt(target.getAttribute('max'), 10);
                    const value = parseInt(target.value, 10);

                    if (value > max) {
                        target.value = max; // Set the value back to the maximum
                    }
                }
            });
        </script>
    @endsection
