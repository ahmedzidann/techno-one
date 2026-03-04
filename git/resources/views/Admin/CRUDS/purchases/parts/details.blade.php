<tr id="tr-{{ $id }}">
    {{--                <th>1</th> --}}
    <th>
        <div class="d-flex flex-column mb-7 fv-row col-sm-2 " style="width: 100%;">
            <label for="productive_id-{{ $id }}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> </span>
            </label>
            <select class="changeKhamId" data-id="{{ $id }}" name="productive_id[]"
                id='productive_id-{{ $id }}' style='width: 200px;'>
                <option selected disabled value='0'>- ابحث عن المنتج -</option>
            </select>
        </div>
    </th>
    <th>
        <input type="text" disabled id="productive_code-{{ $id }}" style="width: 100%;">
    </th>
    <th>
        <input type="text" value="0" name="batch_number[]" id="batch_number-{{ $id }}"
           style="width: 100px; text-align: center;">

    </th>
    {{-- <th>
        <input type="text" disabled id="unit-{{ $id }}">

    </th> --}}
    <th style="padding: 8px;">
        <input type="date" value="{{ date('Y-m-d') }}" id="exp_date-{{ $id }}" name="exp_date[]"
            class="form-control" style="width: 120px; text-align: center;">
    </th>
    <th>
        <input data-id="{{ $id }}" class="form-control navigable" onchange="callTotal()" type="number"
            value="1" min="1" name="amount[]" id="amount-{{ $id }}"style="width: 100px; text-align: center;">

    </th>
    <th>
        <input step=".1" class="form-control navigable" data-id="{{ $id }}" onchange="callTotal()"
            type="number" value="1" min="1" name="productive_buy_price[]"
            id="productive_buy_price-{{ $id }}"style="width: 100px; text-align: center;">

    </th>
    <th>
        <input type="number" class="form-control navigable" value="0" min="0" name="bouns[]"
            id="bouns-{{ $id }}"style="width: 100px; text-align: center;">
    </th>
    <th>
        <input type="number" class="form-control navigable" value="0" min="0" name="discount_percentage[]"
            id="discount_percentage-{{ $id }}"style="width: 100px; text-align: center;" onkeyup="callTotal()">
    </th>
    <th>
        <input type="number" class="form-control navigable" value="0" min="0" name="first_discount[]"
            id="first_discount-{{ $id }}"style="width: 100px; text-align: center;" onkeyup="callTotal()">
        <!-- Adjusted width -->
    </th>
    <th>
        <input type="number" class="form-control navigable" value="0" min="0" name="second_discount[]"
            id="second_discount-{{ $id }}"style="width: 100px; text-align: center;" onkeyup="callTotal()">
        <!-- Adjusted width -->
    </th>
    <th>
        <input type="number" class="form-control" readonly value="0" min="0" name="likely_discount[]"
            id="likely_discount-{{ $id }}"style="width: 100px; text-align: center;" onkeyup="callTotal()">
        <!-- Adjusted width -->
    </th>
    <th>
        <input type="number" class="form-control navigable" value="0" min="0" name="tax[]" id="tax-{{ $id }}"
           style="width: 100px; text-align: center;" onkeyup="callTotal()">
        <!-- Adjusted width -->
    </th>
    <th>
        <input type="number" disabled value="1" min="1" name="total[]" id="total-{{ $id }}"
           style="width: 100px; text-align: center;">

    </th>
    <th>
        <button class="btn rounded-pill btn-danger waves-effect waves-light delete-sup" data-id="{{ $id }}">
            <span class="svg-icon svg-icon-3">
                <span class="svg-icon svg-icon-3">
                    <i class="fa fa-trash"></i>
                </span>
            </span>
        </button>
    </th>
</tr>
