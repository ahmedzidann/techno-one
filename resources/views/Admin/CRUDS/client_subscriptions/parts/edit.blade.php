<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('client-subscriptions.update',$row->id)}}">
    @csrf
    @method('PUT')
    <div class="row g-4">

        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <!--begin::Label-->
            <label for="title" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">عنوان فئة الخصم</span>
            </label>
            <!--end::Label-->
            <input id="title" required type="text" class="form-control form-control-solid"  name="title" value="{{$row->title}}"/>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <!--begin::Label-->
            <label for="discount" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">نسبة الخصم</span>
            </label>
            <!--end::Label-->
            <input id="discount" required type="number" class="form-control form-control-solid"  name="discount" value="{{ $row->discount }}"/>
        </div>

    </div>
</form>
