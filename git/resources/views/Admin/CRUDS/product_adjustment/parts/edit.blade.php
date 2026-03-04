<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST"
    action="{{ route('product-adjustments.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="report_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الطلب</span>
            </label>
            <!--end::Label-->
            <input id="report_number" readonly required type="text" class="form-control form-control-solid"
                name="report_number" value="{{ $row->report_number }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ الطلب</span>
            </label>
            <!--end::Label-->
            <input id="date" required type="date" class="form-control form-control-solid" name="date"
                value="{{ $row->date }}" />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="storage_edit_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المخزن</span>
            </label>
            <select id='storage_id' name="storage_id" class="form-control">
                @foreach ($storages as $storage)
                    <option value="{{ $storage->id }}" {{ $row->storager_id == $storage->id ? 'selected' : '' }}>
                        {{ $storage->title }}</option>
                @endforeach
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="supervisor_edit_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> مسؤول المخزن</span>
            </label>
            <select id='supervisor_id' name="supervisor_id" class="form-control">
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" {{ $row->supervisor_id == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="product_edit_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المنتج</span>
            </label>
            <select id='product_id' name="product_id" class="form-control">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $row->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="type" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> النوع</span>
            </label>
            <select id='type' name="type" class="form-control">
                <option value="1" {{ $row->type == 1 ? 'selected' : '' }}>زيادة</option>
                <option value="2" {{ $row->type == 2 ? 'selected' : '' }}>نقص</option>
            </select>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="type" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الكمية</span>
            </label>
            <input type="number" value="{{ $row->amount }}" min="1" name="amount" id="amount">
        </div>
    </div>
</form>
