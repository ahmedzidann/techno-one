<tr id="tr-{{$id}}"  >
    {{--                <th>1</th>--}}
    <th >
        <div class="d-flex flex-column mb-7 fv-row col-sm-2 ">
            <label for="productive_id-{{$id}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  </span>
            </label>
            <select class="changeKhamId" data-id="{{$id}}" name="productive_id[]" id='productive_id-{{$id}}' style='width: 200px;'>
                <option selected disabled value='0'>- ابحث عن المنتج  -</option>
            </select>
        </div>
    </th>
    <th >
        <input type="text" disabled id="productive_code-{{$id}}">
    </th>
    <th >
        <input type="text" disabled id="unit-{{$id}}">

    </th>
    <th>
        <input data-id="{{$id}}" onchange="callTotal()" type="number" value="1" min="1"  name="amount[]" id="amount-{{$id}}">

    </th>
    <th>
        <input step=".1" data-id="{{$id}}" onchange="callTotal()" type="number" value="1" min="1"  name="productive_buy_price[]" id="productive_buy_price-{{$id}}">

    </th>
    <th>
        <input  type="number" disabled value="1" min="1"  name="total[]" id="total-{{$id}}">

    </th>
    <th>
        <button   class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
                  data-id="{{$id}}">
                    <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </span>
        </button>
    </th>
</tr>
