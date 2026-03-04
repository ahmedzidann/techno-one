<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('clients.update', @$row->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-4">

    <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="region_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> نوع العميل </span>
            </label>

           <select id="client_type" name="client_type" class="form-control">
            @foreach(config('enums.client_types') as $key => $label)
          <option value="{{ $key }}" {{ $row->client_type == $key ? 'selected' : '' }}>
            {{ $label }}
          </option>
         @endforeach
         </select>

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الاسم</span>
            </label>
            <!--end::Label-->
            <input id="name" required type="text" class="form-control form-control-solid" name="name"
                value="{{ @$row->name }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="code" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الكود</span>
            </label>
            <!--end::Label-->
            <input id="code" required type="text" class="form-control form-control-solid" name="code"
                value="{{ @$row->code }}" />
        </div>


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="phone" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الهاتف</span>
            </label>
            <!--end::Label-->
            <input id="phone" required type="text" class="form-control form-control-solid" name="phone"
                value="{{ @$row->phone }}" />
        </div>

        

        


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="governorate_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المحافظة</span>
            </label>

            <select id="governorate_id" name="governorate_id" class="form-control">
                <option selected disabled>اختر المحافظة</option>
                @foreach ($governorates as $governorate)
                    <option @if ($governorate->id == @$row->governorate_id) selected @endif value="{{ $governorate->id }}">
                        {{ $governorate->title }}</option>
                @endforeach
            </select>

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="city_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المدينة</span>
            </label>

            <select id="city_id" name="city_id" class="form-control">
                <option selected disabled>اختر المدينة </option>
                @foreach ($cities as $city)
                    <option @if ($city->id == @$row->city_id) selected @endif value="{{ $city->id }}">
                        {{ $city->title }}</option>
                @endforeach
            </select>

        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="region_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المنطقة</span>
            </label>

            <select id="region_id" name="region_id" class="form-control">
                <option selected disabled>اختر المدينة أولا</option>
                @foreach ($regions as $region)
                    <option @if ($region->id == @$row->region_id) selected @endif value="{{ $region->id }}">
                        {{ $region->title }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="commercial_register" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">السجل التجاري</span>
            </label>
            <!--end::Label-->
            <input id="commercial_register" required type="text" class="form-control form-control-solid"
                name="commercial_register" value="{{ @$row->commercial_register }}" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="tax_card" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">البطاقة الضريبية</span>
            </label>
            <!--end::Label-->
            <input id="tax_card" required type="text" class="form-control form-control-solid" name="tax_card"
                value="{{ @$row->tax_card }}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="previous_indebtedness" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">المديونية السابقة</span>
            </label>
            <!--end::Label-->
            <input id="previous_indebtedness" required type="number" class="form-control form-control-solid"
                name="previous_indebtedness" value="{{ @$row->previous_indebtedness }}" />
        </div>

         <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="previous_indebtedness" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الرقم السري للتطبيق </span>
            </label>
            <!--end::Label-->
            <input id="password"  type="text" class="form-control form-control-solid"
                name="password" value="" />
        </div>


        <div class="col-md-12 my-4">
            <label for="address"> العنوان </label>

            <div class="form-floating ">

                <textarea class="form-control " name="address" placeholder="" id="address">{{ @$row->address }}</textarea>
            </div>
        </div>




    </div>
</form>
