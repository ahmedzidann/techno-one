<div class="d-flex flex-column mb-7 fv-row col-sm-4">
    <!--begin::Label-->
    <label for="supplier_select" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
        <span class="required mr-1"></span>
    </label>
    <!--end::Label-->
    <select class="form-control" id="supplier_select">
        <option selected disabled  >اختر  المورد</option>

        @foreach($suppliers as $supplier)

            <option value="{{$supplier->id}}">{{$supplier->name}}</option>

        @endforeach

    </select>
</div>
