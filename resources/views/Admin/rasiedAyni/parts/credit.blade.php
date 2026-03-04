
<!--begin::Form-->
<div id="form-load">
<form id="credit-form" enctype="multipart/form-data" method="POST" action="{{route('rasied_ayni.store')}}">
    @csrf
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="amount" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الكمية</span>
            </label>
            <!--end::Label-->
            <input id="amount" min="1" required type="number" class="form-control form-control-solid" name="amount" value=""/>
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="branch_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الفرع</span>
            </label>

            <select id="branch_id" name="branch_id" class="form-control">
                <option selected disabled>اختر الفرع</option>
                @foreach($branches as $branch)
                    <option value="{{$branch->id}}"> {{$branch->title}}</option>
                @endforeach
            </select>

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المخزن</span>
            </label>

            <select id="storage_id" name="storage_id" class="form-control">
                <option selected disabled>اختر الفرع اولا</option>

            </select>

        </div>

        <input type="hidden" name="productive_id" value="{{$productive->id}}">

        {{-- <div class="d-flex flex-column mb-7 fv-row col-sm-3">
            <!--begin::Label-->
            <label for="type" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> النوع</span>
            </label>

            <select id="type" name="type" class="form-control">
                <option selected disabled>اختر  النوع</option>
                <option value="wholesale">جملة</option>
                <option value="department">قطاعي</option>

            </select>

        </div> --}}



    </div>

    <button form="credit-form" type="submit" id="credit-submit" class="btn btn-primary my-2">
        <span class="indicator-label">اتمام</span>
    </button>
</form>
</div>

<div class="card-body">
    <table id="productive_table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
           style="width:100%">
        <thead>
        <tr>
            <th>#</th>
            <th>المنتج</th>
            <th>الفرع</th>
            <th>المخزن</th>
            <th>الكمية</th>
            <th>العمليات</th>
        </tr>
        </thead>
        <tbody id="creditTableBody">
          @include('Admin.rasiedAyni.parts.table')
        </tbody>
    </table>
</div>
<script>
    $("#productive_table").DataTable({
        processing: true,
        // pageLength: 50,
        paging: true,
        dom: 'Bfrltip',

        bLengthChange: true,


        "language":<?php echo json_encode(datatable_lang());?>,

        searching: true,
        destroy: true,
        info: false,


    });

</script>
