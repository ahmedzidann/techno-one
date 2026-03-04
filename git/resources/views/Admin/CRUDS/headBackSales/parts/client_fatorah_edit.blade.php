<div class="col-md-10" style="width: 100%;">
    <table id="table-details" class="table table-bordered dt-responsive nowrap table-striped align-middle"
        style="width: 100%;">
        <thead>
            <tr>
                <th> #</th>
                <th> المنتج</th>
                <th> كود المنتج</th>
                <th>الوحدة</th>
                <th> الكمية</th>
                <th>سعر البيع</th>
                <th>بونص</th>
                <th>نسبة الخصم</th>
                <th>رقم التشغيلة</th>$hadbackInvoice->products_ids
                <th> القيمة الاجمالية</th>
                <th>العمليات</th>
            </tr>
        </thead>
        <tbody id="details-container">
            @foreach ($details as $key => $pivot)
                <tr id="tr-{{ $key }}">
                    {{--                <th>1</th> --}}
                    <th>
                        <input type="checkbox" name="check_data[{{ $key }}]" value="{{ $pivot->productive_id }}"
                            onchange="callTotal()"
                            {{ array_key_exists($key, $hadbackInvoice->products_ids) && $hadbackInvoice->products_ids[$key] == $pivot->productive_id ? 'checked' : '' }}>
                        </th>
                    <th style="width: 200px;">
                        <div class="d-flex flex-column mb-7 fv-row col-sm-2 ">
                            <label for="productive_id-{{ $key }}"
                                class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required mr-1"> </span>
                            </label>
                            <select class="form-control changeKhamId" data-id="{{ $key }}"
                                name="productive_id[]" id='productive_id-{{ $key }}' readonly
                                style="width: 200px;" style="pointer-events: none;">
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
                        <input data-id="{{ $key }}" onchange="callTotal()" onkeyup="callTotal()"
                            type="number" value="{{ $hadbackInvoiceDetails->where('productive_id', $pivot->productive_id)->first()?->amount ?? $pivot->amount }}" min="1" name="amount[]"
                            id="amount-{{ $key }}" style="width: 100%;"  max="{{ $pivot->amount }}">

                    </th>
                    <th>
                        <input data-id="{{ $key }}" step=".1" onchange="callTotal()"
                            onkeyup="callTotal()" type="number" value="{{ $pivot->productive_sale_price }}"
                            min="1" name="productive_sale_price[]"
                            id="productive_sale_price-{{ $key }}" style="width: 100%;" readonly>

                    </th>
                    <th style="padding: 8px;">
                        <input data-id="{{ $key }}" step=".1" type="number" value="{{ $pivot->bouns }}"
                            min="0" name="bouns[]" id="bouns-{{ $key }}"
                            style="width: 100px; text-align: center;" readonly>
                    </th>
                    <th style="padding: 8px;">
                        <input data-id="{{ $key }}" step=".1" type="number"
                            value="{{ $pivot->discount_percentage }}" min="0" name="discount_percentage[]"
                            id="discount_percentage-{{ $key }}" style="width: 100px; text-align: center;"
                            onkeyup="callTotal()" readonly>
                    </th>
                    <th style="padding: 8px;">
                        <input data-id="{{ $key }}" step=".1" type="number"
                            value="{{ $pivot->batch_number }}" min="0" name="batch_number[]"
                            id="batch_number-{{ $key }}" style="width: 100px; text-align: center;" readonly>
                    </th>
                    <th>
                        <input type="number" readonly value="{{ $pivot->total }}" min="1" name="total[]"
                            id="total-{{ $key }}" style="width: 100%;" readonly>

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
                <th colspan="2" style="text-align: center; background-color: yellow">الاجمالي قبل الخصم الكلي</th>
                <th colspan="2" id="total_productive_sale_price"
                    style="text-align: center; background-color: #6c757d;color: white">
                </th>
                <th colspan="1" style="text-align: center; background-color: aqua">نسبة الخصم الكلية</th>
                <th colspan="2" style="text-align: center; background-color: gray">
                    <input type="number" id="total_discount" value="{{ $row->total_discount }}" min="0"
                        max="99" name="total_discount" style="width: 100%;" onkeyup="totalAfterDiscount()"
                        readonly> <!-- Adjusted width -->
                </th>
                <th colspan="2" style="text-align: center; background-color: rgb(196, 251, 30)"> الاجمالي بعد الخصم
                    الكلي</th>
                <th colspan="1" style="text-align: center; background-color: rgb(173, 222, 185)">
                    <input type="text" id="total_after_discount" value="" name="total_discount"
                        style="width: 100%;" readonly> <!-- Adjusted width -->
                </th>

            </tr>
        </tfoot>
    </table>
</div>

</div>

<button form="form" type="submit" id="submit" class="btn btn-primary">
    <span class="indicator-label">اتمام</span>
</button>
