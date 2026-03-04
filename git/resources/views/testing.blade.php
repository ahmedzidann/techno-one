




{{--<html lang="en">--}}
{{--<head>--}}
{{--    <!-- Required meta tags -->--}}
{{--    <meta charset="utf-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}

{{--    <!-- Bootstrap CSS -->--}}

{{--    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}

{{--    <title>Hello, world!</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<h1>Hello, world!</h1>--}}



{{--<div class="form-group col-md-3">--}}
{{--    <label >الطالب</label>--}}
{{--    <select  name="data_post[trainer_id_fk]"  id="clients_list" onchange="get_data($(this).val());" class="form-control select-ajex js-example-basic-single">--}}


{{--    </select>--}}


{{--</div>--}}





{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
{{--<script>--}}
{{--    $.ajaxSetup({--}}
{{--        headers: {--}}
{{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--        }--}}
{{--    });--}}
{{--    $(document).ready(function () {--}}
{{--        /* $('select').select2({--}}
{{--             minimumResultsForSearch: 1,// at least 20 results must be displayed--}}
{{--             dir: "rtl",--}}
{{--             language: "ar"--}}
{{--         });*/--}}

{{--        --}}{{--$("#clients_list").select2({--}}
{{--        --}}{{--    ajax: {--}}
{{--        --}}{{--        url: "{{route('admin.getClients')}}",--}}
{{--        --}}{{--        type: "post",--}}
{{--        --}}{{--        dataType: 'json',--}}
{{--        --}}{{--        delay: 250,--}}
{{--        --}}{{--        data: function (params) {--}}
{{--        --}}{{--            return {--}}
{{--        --}}{{--                searchTerm: params.term, // search term--}}
{{--        --}}{{--                page: params.page || 1,--}}
{{--        --}}{{--                "_token": "{{ csrf_token() }}",--}}

{{--        --}}{{--            };--}}
{{--        --}}{{--        },--}}
{{--        --}}{{--        processResults: function (data, params) {--}}
{{--        --}}{{--            // parse the results into the format expected by Select2--}}
{{--        --}}{{--            // since we are using custom formatting functions we do not need to--}}
{{--        --}}{{--            // alter the remote JSON data, except to indicate that infinite--}}
{{--        --}}{{--            // scrolling can be used--}}
{{--        --}}{{--            params.page = params.page || 1;--}}

{{--        --}}{{--            return {--}}
{{--        --}}{{--                results: data.items,--}}
{{--        --}}{{--                pagination: {--}}
{{--        --}}{{--                    more: (params.page * 10) < data.total--}}
{{--        --}}{{--                }--}}
{{--        --}}{{--            };--}}
{{--        --}}{{--        },--}}


{{--        --}}{{--        cache: true--}}
{{--        --}}{{--    },--}}
{{--        --}}{{--    placeholder: 'اختر من الطلاب',--}}
{{--        --}}{{--    minimumResultsForSearch: 1, // at least 20 results must be displayed--}}
{{--        --}}{{--    minimumInputLength: 0,--}}
{{--        --}}{{--    dir: "rtl",--}}
{{--        --}}{{--    language: "ar"--}}
{{--        --}}{{--});--}}


{{--      --}}

{{--            $("#clients_list").select2({--}}
{{--            placeholder: 'Breed...',--}}
{{--            width: '350px',--}}
{{--            allowClear: true,--}}
{{--            ajax: {--}}
{{--                url: "{{route('admin.getClients')}}",--}}
{{--                dataType: 'json',--}}
{{--                data: function(params) {--}}
{{--                    return {--}}
{{--                        term: params.term || '',--}}
{{--                        page: params.page || 1--}}
{{--                    }--}}
{{--                },--}}
{{--                cache: true--}}
{{--            }--}}
{{--        })--}}

{{--    });--}}


{{--</script>--}}

{{--<script>--}}

{{--    $(document).ready(function() {--}}
{{--        $('select').on('scroll', function() {--}}
{{--            var select = $(this);--}}
{{--            alert(true)--}}
{{--            if (select.scrollTop() + select.innerHeight() >= select[0].scrollHeight) {--}}
{{--                console.log('Scrolled to the bottom');--}}
{{--                alert(true)--}}
{{--                // Do something when scrolled to the bottom--}}
{{--            }--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}

{{--<script>--}}
{{--    $(document).on('scroll','#clients_list',function (e){--}}
{{--        alert(true)--}}
{{--    })--}}
{{--</script>--}}

{{--</body>--}}
{{--</html>--}}





    <!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>select2 with pagination</h1>
    </div>
    <select id='channel_id' style='width: 200px;'>
        <option value='0'>- Search Channel -</option>
    </select>
</div>

<button onclick="makeSelect()">add</button>
<script type="text/javascript" src='//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
<!--<![endif]-->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function makeSelect(){
        $("#channel_id").select2({
            placeholder: 'Channel...',
            // width: '350px',
            allowClear: true,
            ajax: {
                url: '{{route('admin.getClients')}}',
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
        }); // alert("uu");

    }
</script>

<script >




    (function() {

        $("#channel_id").select2({
            placeholder: 'Channel...',
            // width: '350px',
            allowClear: true,
            ajax: {
                url: '{{route('admin.getClients')}}',
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
</body>
</html>
