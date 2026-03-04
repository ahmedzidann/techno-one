<tr id="tr-{{ $id }}">
    {{--                <th>1</th> --}}
    <th scope="row">
        <div class="d-flex flex-column mb-7 fv-row col-sm-2 ">
            <label for="productive_id-{{ $id }}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> </span>
            </label>
            <select class="changeKhamId" data-id="{{ $id }}" name="productive_id[]"
                id='productive_id-{{ $id }}' style='width: 200px;'>
                <option selected disabled value='0'>- ابحث عن المنتج -</option>
            </select>
        </div>
    </th>
    {{--                    <th> --}}
    {{--                        <input type="text" disabled id="productive_code-1"> --}}
    {{--                    </th> --}}
    {{--                    <th> --}}
    {{--                        <input type="text" disabled id="unit-1"> --}}

    {{--                    </th> --}}
    <th>
        {{-- <select data-id="{{$id}}" id="type-{{$id}}" name="type[]" class="form-control getDestructionPrice">
            <option value="wholesale">جملة</option>
            <option value="department">قطاعي</option>

        </select> --}}
        <select class="form-control select2" data-id="{{ $id }}" name="batch_number[]"
            id="batch_number-{{ $id }}" style="width: 100%;" onchange="getPrice({{ $id }})">

        </select>
    </th>
    <th>
        <input data-id={{ $id }}"" type="number" value="1" min="1" name="productive_sale_price[]"
            class="changeTotal" id="productive_sale_price-{{ $id }}">

    </th>
    <th>
        <input data-id={{ $id }}"" type="number" value="1" min="1" name="productive_buy_price[]"
            class="changeTotal" id="productive_buy_price-{{ $id }}">

    </th>
    <th>
        <input data-id="{{ $id }}" type="number" value="1" min="1" name="amount[]"
            class="changeTotal" id="amount-{{ $id }}">

    </th>
    <th>
        <input data-id="{{ $id }}" disabled type="number" value="1" min="1" name="total[]"
            id="total-{{ $id }}">

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
