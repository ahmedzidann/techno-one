<form id="form" enctype="multipart/form-data" method="POST" action="{{route('productions.store')}}">
    @csrf
    <div class="row my-4 g-4">



        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="production_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">  تاريخ المحضر</span>
            </label>
            <!--end::Label-->
            <input id="production_date" required type="date" class="form-control form-control-solid"
                   name="production_date"
                   value="{{date('Y-m-d')}}"/>
        </div>




        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">   المخزن</span>
            </label>
            <select id='storage_id' name="storage_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن المخزن</option>
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
                <tr id="tr-1">
                    {{--                <th>1</th>--}}
                    <th>
                        <div class="d-flex flex-column mb-7 fv-row col-sm-2 ">
                            <label for="productive_id"
                                   class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required mr-1">  </span>
                            </label>
                            <select class="changeTamId" data-id="1" name="productive_id[]" id='productive_id'
                                    style='width: 200px;'>
                                <option selected disabled value='0'>- ابحث عن المنتج -</option>
                            </select>
                        </div>
                    </th>
                    <th>
                        <input type="text" disabled id="productive_code-1">
                    </th>
                    <th>
                        <input type="text" disabled id="unit-1">

                    </th>
                    <th>
                        <input data-id="1" onchange="callTotal()" onkeyup="callTotal()" type="number" value="1" min="1"
                               name="amount[]" id="amount-1">

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
