<div class="d-flex flex-column mb-7 fv-row col-sm-4">
    <!--begin::Label-->
    <label for="sub_productive_select" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
        <span class="required mr-1"></span>
    </label>
    <!--end::Label-->
    <select class="form-control" id="sub_productive_select">
        <option selected disabled  >اختر المنتج الخام</option>

        @foreach($subProductive as $productive)

            <option value="{{$productive->id}}">{{$productive->name}}</option>

        @endforeach

    </select>
</div>
