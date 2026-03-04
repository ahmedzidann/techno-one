<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('store-managers.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row my-4 g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="sales_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الطلب</span>
            </label>
            <input id="sales_number" required type="text" class="form-control form-control-solid" name="sales_number"
                value="{{ $row->sales_number }}" readonly />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="sales_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ الطلب</span>
            </label>
            <input id="sales_date" required type="date" class="form-control form-control-solid" name="sales_date"
                value="{{ $row->sales_date }}" readonly />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="pay_method" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> طريقة الشراء </span>
            </label>
            <select id="pay_method" name="pay_method" class="form-control" readonly>
                <option selected disabled>اختر طريقة الشراء</option>
                @if ($row->pay_method == 'cash')
                    <option selected value="cash">كاش</option>
                @else
                    <option @if ($row->pay_method == 'debit') selected @endif value="debit">اجل</option>
                @endif
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المخزن</span>
            </label>
            <select id="storage_id" name="storage_id" class="form-control" style="width: 200px;" readonly>
                <option value="{{ $row->storage_id }}" selected>{{ $row->storage->title ?? '' }}</option>
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="client_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المورد</span>
            </label>
            <select id="client_id" name="client_id" style="width: 200px;" readonly>
                <option value="{{ $row->client_id }}" selected>{{ $row->client->name ?? '' }}</option>
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="fatora_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الفاتورة</span>
            </label>
            <input id="fatora_number" required type="text" class="form-control form-control-solid"
                name="fatora_number" value="{{ $row->fatora_number }}" readonly />
        </div>

        <div class="col-md-10" style="width: 100%;">
            <table id="table-details" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width: 100%;">
                <thead>
                    <tr>
                        <th> المنتج</th>
                        <th> كود المنتج</th>
                        <th>الوحدة</th>
                        <th> الكمية</th>
                        <th>سعر البيع</th>
                        <th>بونص</th>
                        <th>نسبة الخصم</th>
                        <th>رقم التشغيلة</th>
                        <th> القيمة الاجمالية</th>
                        <th> الحالة</th>
                        <th> ملاحظات</th>
                    </tr>
                </thead>
                <tbody id="details-container">
                    @foreach (\App\Models\SalesDetails::where('sales_id', $row->id)->get() as $key => $pivot)
                        <tr id="tr-{{ $key }}">
                            <th>
                                <div class="d-flex flex-column mb-7 fv-row col-sm-2 " style="width: 100%;">
                                    <label for="productive_id-{{ $key }}"
                                        class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required mr-1"> </span>
                                    </label>
                                    <select class="changeKhamId" data-id="{{ $key }}" name="productive_id[]"
                                        id='productive_id-{{ $key }}' style="width: 100%;" readonly>
                                        <option selected value="{{ $pivot->productive_id }}">
                                            {{ $pivot->productive->name ?? '' }}</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <input type="text" value="{{ $pivot->productive_code }}" readonly
                                    id="productive_code-{{ $key }}" style="width: 100%;">
                                <input name="company_id[]" data-id="{{ $key }}" type="hidden"
                                    value="{{ $pivot->company_id }}" id="company_id-{{ $key }}">
                            </th>
                            <th>
                                <input type="text" value="{{ $pivot->productive->unit->title ?? '' }}" readonly
                                    id="unit-{{ $key }}" style="width: 100%;">
                            </th>
                            <th>
                                <input data-id="{{ $key }}" type="number" value="{{ $pivot->amount }}"
                                    min="1" name="amount[]" id="amount-{{ $key }}" style="width: 100%;"
                                    readonly>
                            </th>
                            <th>
                                <input data-id="{{ $key }}" step=".1" type="number"
                                    value="{{ $pivot->productive_sale_price }}" min="1"
                                    name="productive_sale_price[]" id="productive_sale_price-{{ $key }}"
                                    style="width: 100%;" readonly>
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" step=".1" type="number"
                                    value="{{ $pivot->bouns }}" min="1" name="bouns[]"
                                    id="bouns-{{ $key }}" style="width: 100px; text-align: center;"
                                    readonly>
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" step=".1" type="number"
                                    value="{{ $pivot->discount_percentage }}" min="0"
                                    name="discount_percentage[]" id="discount_percentage-{{ $key }}"
                                    style="width: 100px; text-align: center;" readonly>
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" step=".1" type="number"
                                    value="{{ $pivot->batch_number }}" min="1" name="batch_number[]"
                                    id="batch_number-{{ $key }}" style="width: 100px; text-align: center;"
                                    readonly>
                            </th>
                            <th>
                                <input type="number" readonly value="{{ $pivot->total }}" min="1"
                                    name="total[]" id="total-{{ $key }}" style="width: 100%;">
                            </th>
                            <th>
                                <span class="badge bg-{{ $pivot->is_prepared == 1 ? 'success' : 'warning' }}">
                                    {{ $pivot->is_prepared == 1 ? 'تم التجهيز' : 'لم يتم التجهيز' }}
                                </span>
                            </th>
                            <th>
                                <textarea name="notes[]" data-id="{{ $pivot->id }}" id="notes-{{ $pivot->id }}" readonly> {{ $pivot->notes }}</textarea>
                            </th>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" style="text-align: center; background-color: yellow">الاجمالي قبل الخصم
                            الكلي</th>
                        <th colspan="2" id="total_productive_sale_price"
                            style="text-align: center; background-color: #6c757d;color: white">{{ $row->total }}
                        </th>
                        <th colspan="2" style="text-align: center; background-color: aqua">نسبة الخصم الكلية</th>
                        <th colspan="2" style="text-align: center; background-color: gray">
                            <input type="number" id="total_discount" value="{{ $row->total_discount }}"
                                min="0" max="99" name="total_discount" style="width: 100%;" readonly>
                        </th>
                        <th colspan="2" style="text-align: center; background-color: rgb(196, 251, 30)"> الاجمالي
                            بعد الخصم الكلي</th>
                        <th colspan="2" style="text-align: center; background-color: rgb(173, 222, 185)">
                            <input type="number" id="total_after_discount" value="{{ $row->total_after_discount }}"
                                min="1" name="total_after_discount" style="width: 100%;" readonly>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <label for="representative_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المندوب</span>
            </label>
            <select id="representative_id" name="representative_id" style="width: 200px;">
            </select>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-3 mt-3">

            <button form="form" type="submit" id="submit" class="btn btn-primary">
                <span class="indicator-label">اتمام</span>
            </button>
        </div>
    </div>
</form>
