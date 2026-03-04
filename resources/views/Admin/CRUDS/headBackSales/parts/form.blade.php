<form id="form" enctype="multipart/form-data" method="POST" action="{{route('head_back_sales.store')}}">
    @csrf
    <div class="row my-4 g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="sales_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  رقم الطلب</span>
            </label>
            <!--end::Label-->
            <input id="sales_number" disabled required type="text"
                   class="form-control form-control-solid" name="sales_number"
                   value="{{$count+1}}"/>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="sales_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  تاريخ الطلب</span>
            </label>
            <!--end::Label-->
            <input id="sales_date" required type="date" class="form-control form-control-solid"
                   name="sales_date"
                   value="{{date('Y-m-d')}}"/>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="pay_method" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  طريقة الشراء </span>
            </label>
            <select id='pay_method' name="pay_method" class="form-control">
                <option selected disabled>اختر طريقة الشراء</option>
                <option value="cash">كاش</option>
                <option value="debit">اجل</option>

            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">   المخزن</span>
            </label>
            <select id='storage_id' name="storage_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن المخزن</option>
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="client_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">   العميل</span>
            </label>
            <select id='client_id' name="client_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن عملاء</option>
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="sale_number_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> فاتورة البيع</span>
            </label>
            <select id='sale_number_id' name="sales_id" style='width: 200px;'>
                <option selected disabled>ادخل فاتورة البيع</option>
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  رقم الفاتورة</span>
            </label>
            <!--end::Label-->
            <input id="fatora_number" required type="text" class="form-control form-control-solid"
                   name="fatora_number"
                   value=""/>
        </div>
        <div id="fatorah">

        </div>

    </div>

    {{-- <button form="form" type="submit" id="submit" class="btn btn-primary">
        <span class="indicator-label">اتمام</span>
    </button> --}}

</form>
