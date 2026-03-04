<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('coupons-converts.store') }}">
    @csrf
    <div class="row g-4">
        
        
        
         <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">تاريخ الفاتوره </span>
            </label>
            <!--end::Label-->
            <input id="date"  type="date" class="form-control form-control-solid" name="date"
                value="" />
        </div>
        
        

        <div class="d-flex flex-column mb-7 fv-row col-sm-3 ml-2">
            <!--begin::Label-->
            <label for="client_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> اختر العميل</span>
            </label>

            <select name="client_id" class="select2 client_id" id='client_id' style='width: 200px;'>
            </select>

        </div>
            <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="type" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class=" mr-1"> العمليه</span>
            </label>

        <select id='type' name="type" class="form-control">
                <option selected disabled>اختر النوع</option>
                <option value="1">اضافة</option>
                <option value="2">خصم</option>
            </select>
            </div>
    
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">عدد الكوبونات</span>
            </label>
            <!--end::Label-->
            <input id="value"  type="text" class="form-control form-control-solid" name="amount"
                value="" />
        </div>

         <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">رقم الفاتورة</span>
            </label>
            <!--end::Label-->
            <input id="value"  type="number" class="form-control form-control-solid" name="invoice_number"
                value="" />
        </div>
        
         <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">قيمه الفاتوره </span>
            </label>
            <!--end::Label-->
            <input id="invoice_value"  type="text" class="form-control form-control-solid" name="invoice_value"
                value="" />
        </div>
        
          <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">ملاحظات </span>
            </label>
            <!--end::Label-->
            <textarea class="form-control" name="notes"> </textarea>
        </div>
        
    </div>
</form>
