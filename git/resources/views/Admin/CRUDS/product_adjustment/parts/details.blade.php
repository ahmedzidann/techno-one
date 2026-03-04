<tr id="tr-{{ $id }}">
    {{--                <th>1</th> --}}
    <th>
        <div class="d-flex flex-column mb-7 fv-row col-sm-2 " style="width: 100%;">
            <label for="productive_id-{{ $id }}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> </span>
            </label>
            <select class="changeKhamId" data-id="{{ $id }}" name="product_id[]"
                id='productive_id-{{ $id }}' style='width: 100%;'>
                <option selected disabled value='0'>- ابحث عن المنتج -</option>
            </select>
        </div>
    </th>
    <th>
        <input type="text" disabled id="productive_code-{{ $id }}" style="width: 100%;">
    </th>
    <th>
        <input type="text" disabled id="unit-{{ $id }}">

    </th>
    <th>
        <input data-id="{{ $id }}" onchange="callTotal()" type="number" value="1" min="1"
            name="amount[]" id="amount-{{ $id }}" style="width: 100%;">

    </th>
    <th>
        <select class="form-control" data-id="{{$id}}" name="type[]" id='type-{{$id}}' style="width: 100%;">
            <!-- Adjusted width -->
            <option selected disabled>النوع</option>
            <option value="1">زيادة</option>
            <option value="2">نقص</option>
        </select>
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
