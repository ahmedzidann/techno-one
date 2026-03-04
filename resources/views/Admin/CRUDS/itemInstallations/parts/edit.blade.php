<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('itemInstallations.update',$row->id)}}">
    @csrf
    @method('PUT')
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="install_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">التاريخ</span>
            </label>
            <!--end::Label-->
            <input id="install_date" required type="date" class="form-control form-control-solid" name="install_date" value="{{$row->install_date}}"/>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="main_productive_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المنتج التام</span>
            </label>

            <select disabled id="main_productive_id" name="main_productive_id" class="form-control">
                <option value="{{$row->productive->id}}" >{{$row->productive->name??''}}</option>

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
            @foreach(\App\Models\ItemInstallationDetails::where('item_installation_id',$row->id)->get() as $key=>$pivot)
            <span style="display: none!important;">
                @php
                  $produvtiveRow=\App\Models\Productive::find($pivot->productive_id);
                @endphp
            </span>
            <tr id="tr-{{$key}}"  >
                {{--                <th>1</th>--}}
                <th >
                    <div class="d-flex flex-column mb-7 fv-row col-sm-4 ">
                        <label for="channel_id-{{$key}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required mr-1"> المادة الخام</span>
                        </label>
                        <select data-id="{{$key}}" name="productive_id[]" id='channel_id-{{$key}}' class="changeKhamId" style='width: 200px;'>
                            <option selected value='{{$produvtiveRow->id}}'>{{$produvtiveRow->name}}</option>
                        </select>
                    </div>

                </th>
                <th ><input value="{{$produvtiveRow->code??''}}"  type="text" class="form-control changeProductive" disabled id="code-{{$key}}"></th>
                <th >  <input value="{{$produvtiveRow->unit->title??''}} " type="text" class="form-control changeProductive" disabled id="unit-{{$key}}"></th>
                <th><input name="amount[]" required type="number" value="{{$pivot->amount}}" min="1" class="form-control"  id="amount-{{$key}}"> </th>
                <th>
                    <button   class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
                              data-id="{{$key}}">
                    <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </span>
                    </button>
                </th>
            </tr>
            @endforeach
            </tbody>
        </table>



        <div class="d-flex justify-content-end">
            <button id="addNewDetails" class="btn btn-primary">اضافة منتج اخر

            </button>
        </div>

    </div>
</form>

<script>

@foreach(\App\Models\ItemInstallationDetails::where('item_installation_id',$row->id)->get() as $key=>$pivot)

    (function() {

    $("#channel_id-{{$key}}").select2({
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




@endforeach
</script>
