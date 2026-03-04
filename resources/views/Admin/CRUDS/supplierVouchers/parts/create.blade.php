<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('supplier_vouchers.store')}}">
    @csrf
    <div class="row g-4">





        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="voucher_date" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">تاريخ الايصال</span>
            </label>
            <!--end::Label-->
            <input id="voucher_date" required type="date" class="form-control form-control-solid" name="voucher_date" value="{{date('Y-m-d')}}"/>
        </div>



        <div class="d-flex flex-column mb-7 fv-row col-sm-4 ">
            <label for="channel_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الموردين</span>
            </label>
            <select name="supplier_id" id='channel_id' style='width: 200px;'>
                <option value='0'>- Search Supplier -</option>
            </select>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="paid" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المبلغ المدفوع</span>
            </label>
            <!--end::Label-->
            <input id="paid" min="1" required type="number" class="form-control form-control-solid" name="paid" value=""/>
        </div>



    </div>
</form>



<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script >




    (function() {

        $("#channel_id").select2({
            placeholder: 'searching For Supplier...',
            // width: '350px',
            allowClear: true,
            ajax: {
                url: '{{route('admin.getSupplier')}}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: true
            }
        });
    })();

</script>
