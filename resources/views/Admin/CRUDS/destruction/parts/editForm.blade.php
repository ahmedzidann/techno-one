<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('destruction.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row my-4 g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="destruction_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم المحضر</span>
            </label>
            <!--end::Label-->
            <input id="destruction_number" required type="number" class="form-control form-control-solid"
                name="destruction_number" value="{{ $row->destruction_number }}" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="destruction_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ المحضر</span>
            </label>
            <!--end::Label-->
            <input id="destruction_date" required type="date" class="form-control form-control-solid"
                name="destruction_date" value="{{ $row->destruction_date }}" />
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





        <table id="table-details" class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"> المنتج</th>
                    {{--                    <th scope="col"> كود المنتج</th> --}}
                    {{--                    <th scope="col">الوحدة</th> --}}
                    <th scope="col" style="width: 200px;">رقم التشغيلة</th>
                    <th scope="col" >سعر الشراء</th>
                    <th scope="col">سعر البيع</th>
                    <th scope="col"> الكمية</th>
                    <th scope="col">الاجمالي</th>
                    <th scope="col">العمليات</th>
                </tr>
            </thead>
            <tbody id="details-container">
                @foreach (\App\Models\DestructionDetails::where('destruction_id', $row->id)->get() as $key => $pivot)
                    <tr id="tr-{{ $key }}">
                        {{--                <th>1</th> --}}
                        <th scope="row">
                            <div class="d-flex flex-column mb-7 fv-row col-sm-2 ">
                                <label for="productive_id-{{ $key }}"
                                    class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required mr-1"> </span>
                                </label>
                                <select class="changeKhamId" data-id="{{ $key }}" name="productive_id[]"
                                    id='productive_id-{{ $key }}' style='width: 200px;'>
                                    <option selected value="{{ $pivot->productive_id }}">
                                        {{ $pivot->productive->name ?? '' }}</option>
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
                            {{-- <select data-id="{{$key}}" id="type-{{$key}}" name="type[]" class="form-control getDestructionPrice">
                                <option @if ($pivot->type == 'wholesale') selected  @endif value="wholesale">جملة</option>
                                <option @if ($pivot->type == 'department') selected  @endif value="department">قطاعي</option>

                            </select> --}}
                            <select class="form-control selectClass" name="batch_number[]" id="batch_number-{{ $key }}"
                                style="width: 100%;" onchange="getPrice({{ $key }})">
                                <option value="{{ $pivot->batch_number }}" selected>{{ $pivot->batch_number }}</option>

                            </select>
                        </th>
                    <th>
                        <input data-id="{{ $key }}" class="changeTotal" type="number" 
                           value="{{ $pivot->productive_sale_price }}"  name="productive_sale_price[]" id="productive_sale_price-{{ $key }}">

                    </th>
                    <th>
                        <input data-id="{{ $key }}" class="changeTotal" type="number" 
                           value="{{ $pivot->productive_buy_price }}"  name="productive_buy_price[]" id="productive_buy_price-{{ $key }}">

                    </th>
                        <th>
                            <input data-id="{{ $key }}" class="changeTotal" type="number"
                                value="{{ $pivot->amount }}" min="1" name="amount[]"
                                id="amount-{{ $key }}">

                        </th>
                        <th>
                            <input disabled data-id="{{ $key }}" type="number"
                                value="{{ $pivot->amount * $pivot->productive_buy_price }}" min="1" name="total[]"
                                id="total-{{ $key }}">

                        </th>
                        <th>
                            <button class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
                                data-id="1">
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
        </table>

        <div class="d-flex justify-content-end">
            <button id="addNewDetails" class="btn btn-primary">اضافة منتج اخر

            </button>
        </div>


    </div>

    <button form="form" type="submit" id="submit" class="btn btn-primary">
        <span class="indicator-label">اتمام</span>
    </button>

</form>
