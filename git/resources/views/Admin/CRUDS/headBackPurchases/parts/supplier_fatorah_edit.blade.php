        <div class="col-md-10">
            <table id="table-details" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:70% !important;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المنتج</th>
                        <th>كود المنتج</th>
                        <th>تاريخ انتهاء الصلاحية</th>
                        <th>الكمية</th>
                        <th>سعر الشراء</th>
                        <th>بونص</th>
                        <th>نسبة الخصم</th>
                        <th>رقم التشغيلة</th>
                        <th>القيمة الاجمالية</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody id="details-container">
                    @foreach ($details as $key => $pivot)
                        <tr id="tr-{{ $key }}">
                            <th>
                                <input type="checkbox" class="check_data" name="check_data[{{ $key }}]"
                                    value="{{ $pivot->productive_id }}" onchange="callTotal()"
                                    {{ array_key_exists($key, $hadbackInvoice->products_ids) && $hadbackInvoice->products_ids[$key] == $pivot->productive_id ? 'checked' : '' }}>
                            </th>
                            <th style="padding: 8px;">
                                <div class="d-flex flex-column mb-7 fv-row col-sm-2">
                                    <select class="form-control changeKhamId" data-id="{{ $key }}"
                                        name="productive_id[]" id='productive_id-{{ $key }}'
                                        style='width: 200px;' readonly>
                                        <option selected value="{{ $pivot->productive_id }}">
                                            {{ $pivot->productive->name ?? '' }}</option>
                                    </select>
                                </div>
                            </th>
                            <th style="padding: 8px;">
                                <input type="text" value="{{ $pivot->productive_code }}" readonly
                                    id="productive_code-{{ $key }}" style="width: 100px; text-align: center;">
                            </th>
                            {{-- <th style="padding: 8px;">
                                <input type="text" value="{{ $pivot->productive->unit->title ?? '' }}" disabled
                                    id="unit-{{ $key }}" style="width: 100px; text-align: center;">
                            </th> --}}
                            <th style="padding: 8px;">
                                <input type="date" value="{{ $pivot->exp_date ?? date('Y-m-d') }}"
                                    id="exp_date-{{ $key }}" name="exp_date[]" class="form-control"
                                    style="width: 120px; text-align: center;" readonly>
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" max="{{ $pivot->amount }}" onchange="callTotal()"
                                    onkeyup="callTotal()" type="number"
                                    value="{{ $hadbackInvoiceDetails->where('productive_id', $pivot->productive_id)->first()?->amount ?? $pivot->amount }}"
                                    min="1" name="amount[]" id="amount-{{ $key }}"
                                    style="width: 100px; text-align: center;">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" onchange="callTotal()" onkeyup="callTotal()"
                                    type="number" value="{{ $pivot->productive_buy_price }}" min="1"
                                    name="productive_buy_price[]" id="productive_buy_price-{{ $key }}"
                                    style="width: 100px; text-align: center;" readonly>
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" type="number" value="{{ $pivot->bouns }}"
                                    name="bouns[]" id="bouns-{{ $key }}"
                                    style="width: 100px; text-align: center;" onkeyup="maxConstraint(this, {{ $pivot->bouns }})">
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" type="number"
                                    value="{{ $pivot->discount_percentage }}" name="discount_percentage[]"
                                    id="discount_percentage-{{ $key }}"
                                    style="width: 100px; text-align: center;" onkeyup="callTotal()" readonly>
                            </th>
                            <th style="padding: 8px;">
                                <input data-id="{{ $key }}" type="number" value="{{ $pivot->batch_number }}"
                                    name="batch_number[]" id="batch_number-{{ $key }}"
                                    style="width: 100px; text-align: center;" readonly>
                            </th>
                            <th style="padding: 8px;">
                                <input type="number" disabled value="{{ $pivot->total }}" name="total[]"
                                    id="total-{{ $key }}" style="width: 100px; text-align: center;" readonly>
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
                        <th colspan="2" style="text-align: center; background-color: yellow">الاجمالي</th>
                        <th colspan="2" id="total_productive_buy_price"
                            style="text-align: center; background-color: #6c757d;color: white">1
                        </th>
                        <th colspan="1" style="text-align: center; background-color: aqua">نسبة الخصم الكلية</th>
                        <th colspan="2" style="text-align: center; background-color: gray">
                            <input type="number" id="total_discount" value="0" min="0" max="99"
                                name="total_discount" style="width: 100%;" onkeyup="totalAfterDiscount()" readonly>
                            <!-- Adjusted width -->
                        </th>
                        <th colspan="2" style="text-align: center; background-color: rgb(196, 251, 30)"> الاجمالي
                            بعد الخصم الكلي</th>
                        <th colspan="1" style="text-align: center; background-color: rgb(173, 222, 185)">
                            <input type="text" id="total_after_discount" value="" name="total_discount"
                                style="width: 100%;" readonly> <!-- Adjusted width -->
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <button form="form" type="submit" id="submit" class="btn btn-primary">
            <span class="indicator-label">اتمام</span>
        </button>
