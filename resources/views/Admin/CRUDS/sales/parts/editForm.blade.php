<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('sales.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row my-4 g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="sales_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الطلب</span>
            </label>
            <!--end::Label-->
            <input id="sales_number" disabled required type="text" class="form-control form-control-solid"
                name="sales_number" value="{{ $row->sales_number }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="sales_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ الطلب</span>
            </label>
            <!--end::Label-->
            <input id="sales_date" required type="date" class="form-control form-control-solid" name="sales_date"
                value="{{ $row->sales_date }}" />
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
            <label for="client_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> العميل</span>
            </label>
            <select id='client_id' name="client_id" style='width: 200px;'>
                <option value="{{ $row->client_id }}">{{ $row->client->name ?? '' }}</option>
            </select>
        </div>
        <div class="table-responsive" style="width: 100%;">
            <table id="table-details" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width: 100%;">
                <thead>
                    <tr>
                        <th> المنتج</th>
                        <th> كود المنتج</th>
                        <th style="width:200px;">رقم التشغيلة</th>
                        <th> الكمية</th>
                        <th>سعر الجمهور</th>
                        <th>بونص</th>
                        <th>نسبة الخصم</th>
                        <th>مرجح الشراء</th>
                        <th>مرجح البيع</th>
                        <th> القيمة الاجمالية</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody id="details-container">
                    @foreach ($details as $key => $pivot)
                        <tr id="tr-{{ $key }}">
                            {{--                <th>1</th> --}}
                            <th>
                                <div class="d-flex flex-column mb-7 fv-row col-sm-2 " style="width: 100%;">
                                    <label for="productive_id-{{ $key }}"
                                        class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required mr-1"> </span>
                                    </label>
                                    <select class="changeKhamId" data-id="{{ $key }}" name="productive_id[]"
                                        id='productive_id-{{ $key }}' style='width: 200px;'readonly
                                        style="pointer-events: none;">
                                        <option selected value="{{ $pivot->productive_id }}">
                                            {{ $pivot->productive->name ?? '' }}</option>
                                    </select>
                                </div>
                            </th>
                            <th>
                                <input type="text" value="{{ $pivot->productive_code }}" disabled
                                    id="productive_code-{{ $key }}" style="width: 100px; text-align: center;">

                                <input name="company_id[]" data-id="{{ $key }}" type="hidden"
                                    value="{{ $pivot->company_id }}" id="company_id-{{ $key }}">
                            </th>
                            <th>
                                <select class="form-control selectClass" data-id="{{ $key }}"
                                    name="batch_number[]" id="batch_number-{{ $key }}"
                                    style="width: 100px; text-align: center;" onchange="getPrice({{ $key }})">
                                    @forelse ($pivot->product->batches ?? [] as $batch)
                                        <option value="{{ $batch->batch_number }}"
                                            {{ $batch->batch_number == $pivot->batch_number ? 'selected' : '' }}>
                                            {{ $batch->batch_number }}
                                        </option>
                                    @empty
                                        <option>لايوجد رقم تشغيلة</option>
                                    @endforelse
                                </select>
                            </th>
                            <th>
                                <input data-id="{{ $key }}" onkeyup="callTotal(); checkBalance(this);"
                                    type="number" value="{{ $pivot->amount }}" min="1" name="amount[]"
                                    id="amount-{{ $key }}" class="form-control navigable"
                                    style="width: 100px; text-align: center;">
                                <input data-id="{{ $key }}" class="form-control navigable" type="hidden"
                                    id="limit_for_request-{{ $key }}"
                                    style="width: 100px; text-align: center;" disabled>
                                <input data-id="{{ $key }}" class="form-control navigable" type="hidden"
                                    id="limit_for_sale-{{ $key }}" style="width: 100px; text-align: center;"
                                    disabled>
                                <input data-id="{{ $key }}" class="form-control navigable" type="hidden"
                                    id="product_balance-{{ $key }}"
                                    style="width: 100px; text-align: center;" disabled>

                            </th>
                            <th>
                                <input data-id="{{ $key }}" step=".1" type="number" min="1"
                                    name="productive_sale_price[]" value="{{ $pivot->productive_buy_price }}"
                                    id="productive_sale_price-{{ $key }}" class="form-control"
                                    style="width: 100px; text-align: center;">

                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" step=".1" type="number"
                                    value="{{ $pivot->bouns }}" min="0" name="bouns[]"
                                    id="bouns-{{ $key }}" class="form-control navigable"
                                    style="width: 100px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" step=".1" type="number"
                                    value="{{ $pivot->likely_discount - $pivot->discount_percentage }}"
                                    min="0" name="discount_percentage[]"
                                    id="discount_percentage-{{ $key }}" class="form-control navigable"
                                    style="width: 100px; text-align: center;" onkeyup="callTotal()">
                            </th>

                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" step=".1" type="number" readonly
                                    value="{{ $pivot->likely_discount }}" min="0" name="likely_discount[]"
                                    id="likely_discount-{{ $key }}" class="form-control "
                                    style="width: 100px; text-align: center;" {{-- onkeyup="callTotal()" --}}>
                            </th>
                            <th>
                                <input data-id="{{ $key }}" type="number" class="form-control" value="{{ $pivot->likely_sale }}" min="0" readonly
                                    name="likely_sale[]" id="likely_sale-{{ $key }}"
                                    style="width: 100px; text-align: center;">
                            </th>
                            <th>
                                <input type="number" disabled value="{{ $pivot->total }}" min="1"
                                    name="total[]" id="total-{{ $key }}"
                                    style="width: 100px; text-align: center;">

                            </th>
                            <th>
                                <button class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
                                    data-id="{{ $key }}">
                                    <span class="svg-icon svg-icon-3">
                                        <span class="svg-icon svg-icon-3">
                                            <i class="fa fa-trash"></i>
                                        </span>
                                    </span>
                                </button>
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
                        <th colspan="1" style="text-align: center; background-color: aqua">نسبة الخصم الكلية</th>
                        <th colspan="2" style="text-align: center; background-color: gray">
                            <input type="number" id="total_discount" value="{{ $row->total_discount }}"
                                min="0" max="99" name="total_discount"
                                style="width: 100px; text-align: center;" onkeyup="totalAfterDiscount()">
                            <!-- Adjusted width -->
                        </th>
                        <th colspan="2" style="text-align: center; background-color: rgb(196, 251, 30)"> الاجمالي
                            بعد الخصم الكلي</th>
                        <th colspan="1" style="text-align: center; background-color: rgb(173, 222, 185)">
                            <input type="text" id="total_after_discount" value="{{ $row->total_after_discount }}"
                                name="total_discount" style="width: 100%;" disabled> <!-- Adjusted width -->
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
