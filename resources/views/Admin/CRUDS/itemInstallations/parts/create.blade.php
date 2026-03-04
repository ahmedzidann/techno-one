<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('itemInstallations.store')}}">
    @csrf
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="install_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">التاريخ</span>
            </label>
            <!--end::Label-->
            <input id="install_date" required type="date" class="form-control form-control-solid" name="install_date" value="{{date('Y-m-d')}}"/>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="main_productive_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المنتج التام</span>
            </label>

            <select id="main_productive_id" name="main_productive_id" class="form-control">
                <option selected disabled>اختر المنتج</option>
                @foreach($mainProductive as $productive)
                    <option value="{{$productive->id}}"> {{$productive->name}}</option>
                @endforeach
            </select>

        </div>


        <table id="table-sub" class="table table-bordered dt-responsive nowrap table-striped align-middle"
               style="width:100%">
            <thead>
            <tr>
{{--                <th>#</th>--}}
                <th> اسم الصنف</th>
                <th>كود الصنف</th>
                <th>  الوحدة</th>
                <th>الكمية </th>
                <th>الحذف</th>
            </tr>
            </thead>
            <tbody id="details-container">
            <tr id="tr-1"  >
{{--                <th>1</th>--}}
                <th >
                    <div class="d-flex flex-column mb-7 fv-row col-sm-4 ">
                        <label for="channel_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1">  </span>
                        </label>
                        <select class="changeKhamId" data-id="1" name="productive_id[]" id='channel_id' style='width: 200px;'>
                            <option value='0'>- Search Productive -</option>
                        </select>
                    </div>
                </th>
                <th data-id="1" class="changeProductive"><input data-id="1" type="text" class="form-control changeProductive" disabled id="code-1"> </th>
                <th data-id="1" class="changeProductive">  <input data-id="1" type="text" class="form-control changeProductive" disabled id="unit-1"></th>
                <th><input name="amount[]" required type="number" value="1" min="1" class="form-control"  id="amount-1"> </th>
                <th>
                    <button   class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
                    data-id="1">
                    <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </span>
                    </button>
                </th>
            </tr>
            </tbody>
        </table>



        <div class="d-flex justify-content-end">
            <button id="addNewDetails" class="btn btn-primary">اضافة منتج اخر

            </button>
        </div>



    </div>
</form>




<script >




    (function() {

        $("#channel_id").select2({
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
    })();

</script>
