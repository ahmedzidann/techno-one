<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('purchases-requests.store') }}">
    @csrf
    <div class="row my-4 g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="purchases_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الطلب</span>
            </label>
            <!--end::Label-->
            <input id="purchases_number" disabled required type="text" class="form-control form-control-solid"
                name="purchases_number" value="{{ $count + 1 }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="purchases_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ الطلب</span>
            </label>
            <!--end::Label-->
            <input id="purchases_date" required type="date" class="form-control form-control-solid"
                name="purchases_date" value="{{ date('Y-m-d') }}" />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="pay_method" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> طريقة الشراء </span>
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
                <span class="required mr-1"> المخزن</span>
            </label>
            <select id='storage_id' name="storage_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن المخزن</option>
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="supplier_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المورد</span>
            </label>
            <select id='supplier_id' name="supplier_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن الموردين</option>
            </select>
        </div>

        {{-- <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الفاتورة</span>
            </label>
            <!--end::Label-->
            <input id="fatora_number" required type="text" class="form-control form-control-solid"
                name="fatora_number" value="" />
        </div> --}}

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="supplier_fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم فاتورة المورد</span>
            </label>
            <!--end::Label-->
            <input id="supplier_fatora_number" required type="text" class="form-control form-control-solid"
                name="supplier_fatora_number" value="" />
        </div>

        <div class="table-responsive">
            <table id="table-details"
                class="table table-bordered dt-responsive nowrap table-striped align-middle display"
                style="width: 100%; table-layout: auto;">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>كود المنتج</th>
                        {{-- <th>الوحدة</th> --}}
                        <th>رقم التشغيلة</th>
                        <th>تاريخ انتهاء الصلاحية</th>
                        <th>الكمية</th>
                        <th>سعر الجمهور</th>
                        <th>بونص</th>
                        <th>نسبة الخصم</th>
                        <th>خصم 1</th>
                        <th>خصم 2</th>
                        <th> الخصم المرجح</th>
                        <th>الضريبة</th>
                        <th>القيمة الاجمالية</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody id="details-container">
                    <tr id="tr-1">
                        <th>
                            <div class="d-flex flex-column mb-7 fv-row col-sm-2" style='width: 100%;'>
                                <label for="productive_id"
                                    class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                </label>
                                <select class="changeKhamId" data-id="1" name="productive_id[]" id='productive_id-1'
                                    style='width: 200px;'> <!-- Adjusted width -->
                                    <option selected disabled value='0'>- ابحث عن المنتج -</option>
                                </select>
                            </div>
                        </th>
                        <th>
                            <input type="text" disabled id="productive_code-1" style="width: 100px; text-align: center;">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="text" value="0" name="batch_number[]" id="batch_number-1"
                                style="width: 100px; text-align: center;"> <!-- Adjusted width -->
                        </th>
                        {{-- <th>
                            <input type="text" disabled id="unit-1" style="width: 100%;"> <!-- Adjusted width -->
                        </th> --}}
                        <th style="padding: 8px;">
                            <input type="date" value="{{ date('Y-m-d') }}" id="exp_date" name="exp_date[]"
                                class="form-control" style="width: 120px; text-align: center;">
                        </th>
                        <th>
                            <input data-id="1" onchange="callTotal()" class="form-control navigable"
                                onkeyup="callTotal()" type="number" value="1" min="1" name="amount[]"
                                id="amount-1" style="width: 100px; text-align: center;">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input data-id="1" step=".1" onchange="callTotal()" onkeyup="callTotal()"
                                type="number" value="1" class="form-control navigable" min="1"
                                name="productive_buy_price[]" id="productive_buy_price-1" style="width: 100px; text-align: center;">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="number" class="form-control navigable" value="0" min="0"
                                name="bouns[]" id="bouns-1" style="width: 100px; text-align: center;"> <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="number" class="form-control navigable" value="0" min="0"
                                name="discount_percentage[]" id="discount_percentage-1" style="width: 100px; text-align: center;"
                                onkeyup="callTotal()">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="number" class="form-control navigable" value="0" min="0"
                                name="first_discount[]" id="first_discount-1" style="width: 100px; text-align: center;"
                                onkeyup="callTotal()">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="number" class="form-control navigable" value="0" min="0"
                                name="second_discount[]" id="second_discount-1" style="width: 100px; text-align: center;"
                                onkeyup="callTotal()">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="number" class="form-control" value="0" min="0" readonly
                                name="likely_discount[]" id="likely_discount-1" style="width: 100px; text-align: center;"
                                {{-- onkeyup="callTotal()" --}}>
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="number" class="form-control navigable" value="0" min="0"
                                name="tax[]" id="tax-1" style="width: 100px; text-align: center;"
                                onkeyup="callTotal()">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="number" disabled value="1" min="1" name="total[]"
                                id="total-1" style="width: 100px; text-align: center;"> <!-- Adjusted width -->
                        </th>
                        <th>
                            <button class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
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
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align: center; background-color: yellow">الاجمالي</th>
                        <th colspan="2" id="total_productive_buy_price"
                            style="text-align: center; background-color: #6c757d;color: white">1
                        </th>
                        <th colspan="2" style="text-align: center; background-color: aqua">نسبة الخصم الكلية</th>
                        <th colspan="2" style="text-align: center; background-color: gray">
                            <input type="number" id="total_discount" value="0" min="0" max="99"
                                name="total_discount" style="width: 100%;" onkeyup="totalAfterDiscount()">
                            <!-- Adjusted width -->
                        </th>
                        <th colspan="2" style="text-align: center; background-color: rgb(196, 251, 30)"> الاجمالي
                            بعد الخصم الكلي</th>
                        <th colspan="3" style="text-align: center; background-color: rgb(173, 222, 185)">
                            <input type="text" id="total_after_discount" value="" name="total_discount"
                                style="width: 100%;" disabled> <!-- Adjusted width -->
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>


        <div class="d-flex justify-content-end">
            <button id="addNewDetails" class="btn btn-primary navigable">اضافة منتج اخر

            </button>
        </div>


    </div>

    <button form="form" type="submit" id="submit" class="btn btn-primary">
        <span class="indicator-label">اتمام</span>
    </button>

</form>
