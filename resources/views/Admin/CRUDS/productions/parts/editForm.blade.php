<form id="form" enctype="multipart/form-data" method="POST" action="{{route('productions.update',$row->id)}}">
    @csrf
    @method('PUT')
    <div class="row my-4 g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="production_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  تاريخ المحضر</span>
            </label>
            <!--end::Label-->
            <input id="production_date" required type="date" class="form-control form-control-solid"
                   name="production_date"
                   value="{{$row->production_date}}"/>
        </div>



        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">   المخزن</span>
            </label>
            <select id='storage_id' name="storage_id" style='width: 200px;'>
                <option value="{{$row->storage_id}}">{{$row->storage->title??''}}</option>
            </select>
        </div>





            <table id="table-details"
                   class="table table-bordered dt-responsive nowrap table-striped align-middle"
                   style="width:100% !important;">
                <thead>
                <tr>
                    <th> المنتج</th>
                    <th> كود المنتج</th>
                    <th>الوحدة</th>
                    <th> الكمية</th>
                    <th>العمليات</th>
                </tr>
                </thead>
                <tbody id="details-container">
                @foreach(\App\Models\ProductionDetails::where('production_id',$row->id)->get() as $key=>$pivot)
                    <tr id="tr-{{$key}}">
                        {{--                <th>1</th>--}}
                        <th>
                            <div class="d-flex flex-column mb-7 fv-row col-sm-2 ">
                                <label for="productive_id-{{$key}}"
                                       class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required mr-1">  </span>
                                </label>
                                <select class="changeKhamId" data-id="{{$key}}" name="productive_id[]"
                                        id='productive_id-{{$key}}'
                                        style='width: 200px;'>
                                    <option selected
                                            value="{{$pivot->productive_id}}">{{$pivot->productive->name??''}}</option>
                                </select>
                            </div>
                        </th>
                        <th>
                            <input type="text" value="{{$pivot->productive_code}}" disabled
                                   id="productive_code-{{$key}}">
                        </th>
                        <th>
                            <input type="text" value="{{$pivot->productive->unit->title??''}}" disabled
                                   id="unit-{{$key}}">

                        </th>
                        <th>
                            <input data-id="{{$key}}" onchange="callTotal()" onkeyup="callTotal()" type="number"
                                   value="{{$pivot->amount}}" min="1"
                                   name="amount[]" id="amount-{{$key}}">

                        </th>

                        <th>
                            <button class="btn rounded-pill btn-danger waves-effect waves-light delete-sup"
                                    data-id="{{$key}}">
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

