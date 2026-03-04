<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('employees.store') }}">
    @csrf
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الاسم</span>
            </label>
            <!--end::Label-->
            <input id="name" required type="text" class="form-control form-control-solid" name="name"
                value="" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="phone_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الهاتف</span>
            </label>
            <!--end::Label-->
            <input id="phone_number" required type="text" class="form-control form-control-solid" name="phone_number"
                value="" />
        </div>



        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="company_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الشركة</span>
            </label>

            <select id="company_id" name="company_id" class="form-control">
                <option selected disabled>اختر الشركة</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}"> {{ $company->title }}</option>
                @endforeach
            </select>

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="storage_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المخزن</span>
            </label>
            <select id="storage_id" name="storage_id" class="form-control">
                <option selected disabled>اختر المخزن</option>
                @foreach ($storages as $storage)
                    <option value="{{ $storage->id }}"> {{ $storage->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>
