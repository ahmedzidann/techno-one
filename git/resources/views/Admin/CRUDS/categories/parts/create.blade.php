<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{route('categories.store')}}">
    @csrf
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-6">
            <!--begin::Label-->
            <label for="title" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الاسم</span>
            </label>
            <!--end::Label-->
            <input id="title" required type="text" class="form-control form-control-solid" name="title" value=""/>
        </div>
   <div class="d-flex flex-column mb-7 fv-row col-sm-12 ">
            <label for="zone_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الصوره</span>
            </label>
          <input type="file" class="dropify" name="image" data-height="150" />
        </div>

        <!-- <div class="d-flex flex-column mb-7 fv-row col-sm-6">
           
            <label for="from_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">التصنيف الرئيسي</span>
            </label>

            <select id="from_id" name="from_id" class="form-control">
                <option selected disabled>اختر</option>
                @foreach($categories as $category)
                    <option value="{{$category->id}}"> {{$category->title}}</option>
                @endforeach
            </select>

        </div> -->


    </div>
</form>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/js/dropify.min.js"></script>

<script>
$(document).ready(function() {
    $('.dropify').dropify({
        messages: {
            'default': 'اسحب و افلت الصورة هنا أو اضغط للرفع',
            'replace': 'اسحب لتغيير الصورة أو اضغط',
            'remove':  'حذف',
            'error':   'حدث خطأ ما'
        }
    });
});
</script>