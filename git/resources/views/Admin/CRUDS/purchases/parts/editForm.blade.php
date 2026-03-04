<form id="form" enctype="multipart/form-data" method="POST"
    action="{{ route('purchases.update', $row->id) }}">
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

        {{-- <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الفاتورة</span>
            </label>
            <!--end::Label-->
            <input id="fatora_number" required type="text" class="form-control form-control-solid"
                name="fatora_number" value="{{ $row->fatora_number }}" />
        </div> --}}

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="supplier_fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم فاتورة المورد</span>
            </label>
            <!--end::Label-->
            <input id="supplier_fatora_number" required type="text" class="form-control form-control-solid"
                name="supplier_fatora_number" value="{{ $row->supplier_fatora_number }}" />
        </div>

        <div class="table-responsive">
            <table id="table-details"  class="table table-bordered dt-responsive nowrap table-striped align-middle display"
                style="width: 100%;">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>كود المنتج</th>
                        <th>رقم التشغيلة</th>
                        {{-- <th>الوحدة</th> --}}
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
                    @foreach (\App\Models\PurchasesDetails::where('purchases_id', $row->id)->get() as $key => $pivot)
                        <tr id="tr-{{ $key }}">
                            <th style="padding: 8px;">
                                <div class="d-flex flex-column mb-7 fv-row col-sm-2">
                                    <select class="changeKhamId" data-id="{{ $key }}" name="productive_id[]"
                                        id='productive_id-{{ $key }}' style='width: 200px;'>
                                        <option selected value="{{ $pivot->productive_id }}">
                                            {{ $pivot->productive->name ?? '' }}</option>
                                    </select>
                                </div>
                            </th>
                            <th style="padding: 8px;">
                                <input type="text" value="{{ $pivot->productive_code }}" disabled
                                    id="productive_code-{{ $key }}" style="width: 100px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" type="text" value="{{ $pivot->batch_number }}"
                                    name="batch_number[]" id="batch_number-{{ $key }}"
                                    style="width: 100px; text-align: center;">
                            </th>
                            {{-- <th style="padding: 8px;">
                                <input type="text" value="{{ $pivot->productive->unit->title ?? '' }}" disabled
                                    id="unit-{{ $key }}" style="width: 100px; text-align: center;">
                            </th> --}}
                            <th style="padding: 8px;">
                                <input type="date" value="{{ $pivot->exp_date ?? date('Y-m-d') }}"
                                    id="exp_date-{{ $key }}" name="exp_date[]" class="form-control"
                                    style="width: 120px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" onchange="callTotal()" onkeyup="callTotal()"
                                    type="number" value="{{ $pivot->amount }}" min="1" name="amount[]"
                                    id="amount-{{ $key }}" style="width: 100px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" step=".1" onchange="callTotal()"
                                    onkeyup="callTotal()" type="number" value="{{ $pivot->productive_buy_price }}"
                                    min="1" name="productive_buy_price[]"
                                    id="productive_buy_price-{{ $key }}"
                                    style="width: 100px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" type="number" value="{{ $pivot->bouns }}"
                                    name="bouns[]" id="bouns-{{ $key }}"
                                    style="width: 100px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" type="number"
                                    value="{{ $pivot->discount_percentage }}" name="discount_percentage[]"
                                    id="discount_percentage-{{ $key }}"
                                    style="width: 100px; text-align: center;" onkeyup="callTotal()">
                            </th>
                            <th>
                                <input type="number" class="form-control navigable" value="{{ $pivot->first_discount }}" min="0"
                                    name="first_discount[]" id="first_discount-{{ $key }}"
                                    style="width: 100px; text-align: center;" onkeyup="callTotal()">
                                <!-- Adjusted width -->
                            </th>
                            <th>
                                <input type="number" class="form-control navigable" value="{{ $pivot->second_discount }}" min="0"
                                    name="second_discount[]" id="second_discount-{{ $key }}"
                                    style="width: 100px; text-align: center;" onkeyup="callTotal()">
                                <!-- Adjusted width -->
                            </th>
                            <th>
                                <input type="number" class="form-control" value="{{ $pivot->likely_discount }}" min="0"
                                    name="likely_discount[]" id="likely_discount-{{ $key }}" readonly
                                    style="width: 100px; text-align: center;" {{-- onkeyup="callTotal()" --}}>
                                <!-- Adjusted width -->
                            </th>
                            <th>
                                <input type="number" class="form-control navigable" value="{{ $pivot->tax }}"
                                    min="0" name="tax[]" id="tax-{{ $key }}" style="width: 100px; text-align: center;"
                                    onkeyup="callTotal()">
                                <!-- Adjusted width -->
                            </th>
                            <th style="padding: 8px;">
                                <input type="number" disabled value="{{ $pivot->total }}" name="total[]"
                                    id="total-{{ $key }}" style="width: 100px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <button class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
                                    data-id="{{ $key }}" style="padding: 5px 10px;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </th>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="1" style="text-align: center; background-color: yellow">الاجمالي</th>
                        <th colspan="2" id="total_productive_buy_price"
                            style="text-align: center; background-color: #6c757d;color: white">{{ $row->total }}
                        </th>
                        <th colspan="1" style="text-align: center; background-color: aqua">نسبة الخصم الكلية</th>
                        <th colspan="2" style="text-align: center; background-color: gray">
                            <input type="number" id="total_discount" value="{{ $row->total_discount }}"
                                min="0" max="99" name="total_discount" style="width: 100px; text-align: center;"
                                onkeyup="totalAfterDiscount()">
                            <!-- Adjusted width -->
                        </th>
                        <th colspan="2" style="text-align: center; background-color: rgb(196, 251, 30)"> الاجمالي
                            بعد الخصم الكلي</th>
                        <th colspan="2" style="text-align: center; background-color: rgb(173, 222, 185)">
                            <input type="text" id="total_after_discount" value="{{ $row->total_after_discount }}"
                                name="total_discount" style="width: 100px; text-align: center;" disabled> <!-- Adjusted width -->
                        </th>
                        <th colspan="4" style="text-align: center; background-color: rgb(173, 222, 185)"></th>
                    </tr>
                </tfoot>
            </table>
        </div>


        <div class="d-flex justify-content-end">
            <button id="addNewDetails" class="btn btn-primary">اضافة منتج اخر

            </button>
        </div>


    </div>

    <button form="form" type="submit" id="submit" class="btn btn-primary">
        <span class="indicator-label">اتمام</span>
    </button>

</form>
