<form id="form" enctype="multipart/form-data" method="POST"
    action="{{ route('head_back_purchases.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row my-4 g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="purchases_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الطلب</span>
            </label>
            <!--end::Label-->
            <input id="purchases_number" disabled required type="text" class="form-control form-control-solid"
                name="purchases_number" value="{{ $row->purchases_number }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="purchases_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ الطلب</span>
            </label>
            <!--end::Label-->
            <input id="purchases_date" required type="date" class="form-control form-control-solid"
                name="purchases_date" value="{{ $row->purchases_date }}" />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="pay_method" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> طريقة الشراء </span>
            </label>
            <select id='pay_method' name="pay_method" class="form-control">
                <option selected disabled>اختر طريقة الشراء</option>
                <option @if ($row->pay_method == 'cash') selected @endif value="cash">كاش</option>
                <option @if ($row->pay_method == 'debit') selected @endif value="debit">اجل</option>

            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المخزن</span>
            </label>
            <select id='storage_id' name="storage_id" style='width: 200px;'>
                <option value="{{ $row->storage_id }}">{{ $row->storage->title ?? '' }}</option>
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="supplier_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المورد</span>
            </label>
            <select id='supplier_id' name="supplier_id" style='width: 200px;'>
                <option value="{{ $row->supplier_id }}">{{ $row->supplier->name ?? '' }}</option>
            </select>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="purchase_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> فاتورة الشراء</span>
            </label>
            <select id='purchase_id' name="purchase_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن فاتورة الشراء</option>
                @if ($row->invoice_purchase)
                    <option value="{{ $row->invoice_purchase->id }}" selected>{{ $row->invoice_purchase->fatora_number }}</option>
                @endif
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الفاتورة</span>
            </label>
            <!--end::Label-->
            <input id="fatora_number" required type="text" class="form-control form-control-solid"
                name="fatora_number" value="{{ $row->fatora_number }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="supplier_fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم فاتورة المورد</span>
            </label>
            <!--end::Label-->
            <input id="supplier_fatora_number" required type="text" class="form-control form-control-solid"
                name="supplier_fatora_number" value="{{ $row->supplier_fatora_number }}" />
        </div>

        <div id="fatorah"></div>

    </div>

    {{-- <button form="form" type="submit" id="submit" class="btn btn-primary">
        <span class="indicator-label">اتمام</span>
    </button> --}}

</form>
