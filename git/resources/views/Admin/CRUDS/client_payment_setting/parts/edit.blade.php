<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST"
    action="{{ route('client-payment-settings.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="payment_category" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> فئة السداد</span>
            </label>

            <select id="payment_category" name="payment_category" class="form-control">
                <option selected disabled>اختر الفئة</option>
                @foreach ($paymentCategories as $value => $category)
                    <option value="{{ $value }}" {{ $row->payment_category == $value ? 'selected' : '' }}>
                        {{ $category }}</option>
                @endforeach
            </select>

        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="month" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الشهر</span>
            </label>
            <select id="month" name="month" class="form-control">
                <option selected disabled>اختر الشهر</option>
                @for ($month = 1; $month <= 12; $month++)
                    <option value="{{ $month }}"  {{ $row->month == $month ? 'selected' : '' }}> {{ $month }}</option>
                @endfor
            </select>

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="title" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">اسم الفترة</span>
            </label>
            <!--end::Label-->
            <input id="title" required type="text" class="form-control form-control-solid" name="title"
                value="{{$row->title}}" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="from_day" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> من يوم</span>
            </label>
            <select id="from_day" name="from_day" class="form-control">
                <option selected disabled>اختر من يوم</option>
                @for ($from_day = 1; $from_day <= 31; $from_day++)
                    <option value="{{ $from_day }}" {{ $row->from_day == $from_day ? 'selected' : '' }}> {{ $from_day }}</option>
                @endfor
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="to_day" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> إلى يوم</span>
            </label>
            <select id="to_day" name="to_day" class="form-control">
                <option selected disabled>اختر إلى يوم</option>
                @for ($to_day = 1; $to_day <= 31; $to_day++)
                    <option value="{{ $to_day }}" {{ $row->to_day == $to_day ? 'selected' : '' }}> {{ $to_day }}</option>
                @endfor
            </select>
        </div>

    </div>
</form>
