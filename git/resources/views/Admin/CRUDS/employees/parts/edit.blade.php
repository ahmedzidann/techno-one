<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('employees.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الاسم</span>
            </label>
            <!--end::Label-->
            <input id="name" required type="text" class="form-control form-control-solid" name="name"
                value="{{ $row->name }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="phone_number" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الهاتف</span>
            </label>
            <!--end::Label-->
            <input id="phone_number" required type="text" class="form-control form-control-solid" name="phone_number"
                value="{{ $row->phone_number }}" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="company_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الشركة</span>
            </label>

            <select id="company_id" name="company_id" class="form-control">
                <option selected disabled>اختر الشركة</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ $row->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->title }}</option>
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
                    <option value="{{ $storage->id }}" {{ $row->storage_id == $storage->id ? 'selected' : '' }}>
                        {{ $storage->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>
