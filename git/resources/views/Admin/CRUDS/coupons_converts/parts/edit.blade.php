<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('coupons-converts.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-4">
        
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ الفاتوره   </span>
            </label>
            <!--end::Label-->
            <input id="date"  type="date" class="form-control form-control-solid" name="date"
                value="{{ $row->date }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3 ml-2">
            <!--begin::Label-->
            <label for="client_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> اختر العميل</span>
            </label>

            <select name="client_id" class="select2 client_id" id='client_id' style='width: 200px;'>
                @if($row->from_user_id == 0)
                <option selected value="{{ $row->to_user_id }}">{{ $row->toUser->name }}</option>
                @else
                  <option selected value="{{ $row->from_user_id }}">{{ $row->fromUser->name }}</option>
                @endif
            </select>

        </div>

     <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">القيمة</span>
            </label>
            <!--end::Label-->
            <input id="value"  type="text" class="form-control form-control-solid" name="amount"
                value="{{ $row->amount }}" />
        </div>

         <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">رقم الفاتورة</span>
            </label>
            <!--end::Label-->
            <input id="value"  type="number" class="form-control form-control-solid" name="invoice_number"
                value="{{ $row->invoice_number }}" />
        </div>
        
         <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">قيمه الفاتوره </span>
            </label>
            <!--end::Label-->
            <input id="invoice_value"  type="text" class="form-control form-control-solid" name="invoice_value"
                value="{{ $row->invoice_value }}" />
        </div>
          <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="value" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">ملاحظات </span>
            </label>
            <!--end::Label-->
            <textarea class="form-control" name="notes"> {{ $row->notes }} </textarea>
        </div>
    </div>
</form>
