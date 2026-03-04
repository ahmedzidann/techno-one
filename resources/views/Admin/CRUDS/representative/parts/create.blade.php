<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('representatives.store') }}">
    @csrf
    <div class="row g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="branch_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الفرع</span>
            </label>

            <select id="branch_id" name="branch_id" class="form-control">
                <option selected disabled>اختر الفرع</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}"> {{ $branch->title }}</option>
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



        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> اسم المندوب بالكامل</span>
            </label>
            <!--end::Label-->
            <input required type="text" class="form-control form-control-solid" placeholder=" اسم المندوب بالكامل "
                name="full_name" value="" />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الهاتف</span>
            </label>
            <!--end::Label-->
            <input type="text" class="form-control form-control-solid" placeholder=" +021234567 " name="phone"
                value="" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> اسم المستخدم</span>
            </label>
            <!--end::Label-->
            <input required type="text" class="form-control form-control-solid" placeholder=" اسم المستخدم"
                name="user_name" value="" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> كلمة المرور</span>
            </label>
            <!--end::Label-->
            <input type="password" class="form-control form-control-solid" placeholder="******* " name="password"
                value="" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> العنوان</span>
            </label>
            <!--end::Label-->
            <input type="text" class="form-control form-control-solid" placeholder=" العنوان" name="address"
                value="" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="type" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> النوع</span>
            </label>

            <select id="type" name="type" class="form-control">
                <option selected disabled>اختر النوع</option>
                    <option value="1"> مندوب</option>
                    <option value="2"> موزع</option>
            </select>

        </div>
    </div>
</form>
<script></script>
