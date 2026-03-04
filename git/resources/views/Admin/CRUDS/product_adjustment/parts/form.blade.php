<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('product-adjustments.store') }}">
    @csrf
    <div class="row my-4 g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="report_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الطلب</span>
            </label>
            <!--end::Label-->
            <input id="report_number" readonly required type="text" class="form-control form-control-solid"
                name="report_number" value="{{ $count + 1 }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> تاريخ الطلب</span>
            </label>
            <!--end::Label-->
            <input id="date" required type="date" class="form-control form-control-solid"
                name="date" value="{{ date('Y-m-d') }}" />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المخزن</span>
            </label>
            <select id='storage_id' name="storage_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن المخزن</option>
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="supplier_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> مسؤول المخزن</span>
            </label>
            <select id='supervisor_id' name="supervisor_id" style='width: 200px;'>
                <option selected disabled>- ابحث عن مسؤول المخزن</option>
            </select>
        </div>
        <div class="col-md-12"> <!-- Changed from col-md-10 to col-md-12 to take full width of the container -->
            <table id="table-details"
                class="table table-bordered dt-responsive nowrap table-striped align-middle display"
                style="width: 100%;"> <!-- Removed !important; it's better to avoid using it unless necessary -->
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>كود المنتج</th>
                        <th>الوحدة</th>
                        <th>الكمية</th>
                        <th>النوع</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody id="details-container">
                    <tr id="tr-1">
                        <th>
                            <div class="d-flex flex-column mb-7 fv-row col-sm-2" style='width: 100%;'>
                                <label for="productive_id"
                                    class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                </label>
                                <select class="changeKhamId" data-id="1" name="product_id[]" id='productive_id'
                                    style="width: 100%;"> <!-- Adjusted width -->
                                    <option selected disabled value='0'>- ابحث عن المنتج -</option>
                                </select>
                            </div>
                        </th>
                        <th>
                            <input type="text" disabled id="productive_code-1" style="width: 100%;">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <input type="text" disabled id="unit-1" style="width: 100%;"> <!-- Adjusted width -->
                        </th>
                        <th>
                            <input data-id="1" onchange="callTotal()" onkeyup="callTotal()" type="number"
                                value="1" min="1" name="amount[]" id="amount-1" style="width: 100%;">
                            <!-- Adjusted width -->
                        </th>
                        <th>
                            <select class="form-control" data-id="1" name="type[]" id='type'
                                style="width: 100%;"> <!-- Adjusted width -->
                                <option selected disabled>النوع</option>
                                    <option value="1">زيادة</option>
                                    <option value="2">نقص</option>
                            </select>
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
                {{-- <tfoot>
                    <tr>
                        <th colspan="3" style="text-align: center; background-color: yellow">الاجمالي</th>
                        <th colspan="3" id="total_productive_buy_price"
                            style="text-align: center; background-color: #6c757d;color: white">1
                        </th>
                    </tr>
                </tfoot> --}}
            </table>
        </div>


        <div class="d-flex justify-content-end">
            <button id="addNewDetails" class="btn btn-primary">اضافة منتج اخر

            </button>
        </div>


    </div>

    <button form="form" type="submit" id="submit" class="btn btn-primary">
        <span class="indicator-label">اتمام</span>
    </button>

</form>
