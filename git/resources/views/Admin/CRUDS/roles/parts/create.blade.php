<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('roles.store') }}">
    @csrf
    <div class="d-flex flex-column mb-7 fv-row col-sm-12">
        <!--begin::Label-->
        <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
            <span class="required mr-1">الاسم</span>
        </label>
        <!--end::Label-->
        <input id="name" required type="text" class="form-control form-control-solid" placeholder=""
            name="name" value="" />
    </div>

    <div class="d-flex justify-content-center my-2">
        <h2>الصلاحيات</h2>
    </div>


    <div class="container">
        @foreach ($permission as $key => $group)
            <div class="group" id="group-{{ $key }}">
                <div class="group-header">
                    <h2>{{ $key }}</h2>
                    <input type="checkbox" class="group-checkbox"
                        onchange="toggleGroup(this, 'group-{{ $key }}')">
                </div>
                <ul>
                    @foreach ($group as $row)
                        <li><label><input type="checkbox" name="permission[]" value="{{ $row->id }}">
                                {{ $row->name }}</label></li>
                    @endforeach
                </ul>
            </div>
        @endforeach

    </div>
</form>
