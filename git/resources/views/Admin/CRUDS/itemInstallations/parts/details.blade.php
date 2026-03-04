<tr id="tr-{{$id}}" data-id="{{$id}}" class="">
{{--    <th>1</th>--}}
    <th >
        <div class="d-flex flex-column mb-7 fv-row col-sm-4 ">
            <label for="channel_id-{{$id}}" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المادة الخام</span>
            </label>
            <select data-id="{{$id}}" name="productive_id[]" id='channel_id-{{$id}}' class="changeKhamId" style='width: 200px;'>
                <option value='0'>- Search Productive -</option>
            </select>
        </div>

    </th>
    <th data-id="{{$id}}" class="changeProductive"><input data-id="{{$id}}" type="text" class="form-control changeProductive" disabled id="code-{{$id}}"> </th>
    <th data-id="{{$id}}" class="changeProductive">  <input data-id="{{$id}}" type="text" class="form-control changeProductive" disabled id="unit-{{$id}}"></th>
    <th><input  required name="amount[]"  value="1" type="number" min="1" class="form-control"  id="amount-{{$id}}"> </th>
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




