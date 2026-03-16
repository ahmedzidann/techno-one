<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('previews.update', $row->id) }}">
    @csrf
    @method('PUT')
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الاسم</span>
            </label>
            <!--end::Label-->
            <input id="name" required type="text" class="form-control form-control-solid" name="name"
                value="{{ $row->name }}" />
        </div>

       

       
       
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="preview_category_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">التصنيف </span>
            </label>

            <select id="preview_category_id" name="preview_category_id" class="form-control">
                <option selected disabled>اختر التصنيف</option>
                @foreach ($categories as $category)
                    <option @if ($row->preview_category_id == $category->id) selected @endif value="{{ $category->id }}">
                        {{ $category->name }}</option>
                @endforeach
            </select>

        </div>


         <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">النقاط</span>
            </label>
            <!--end::Label-->
            <input id="points" required type="text" class="form-control form-control-solid" name="points"
                value="{{ $row->points }}" />
        </div>


        
       
<a href="{{ asset('storage/' . $row->image) }}" target="_blank">افتح الصورة</a>
        <div class="d-flex flex-column mb-7 fv-row col-sm-12 ">
            <label for="zone_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الصوره</span>
            </label>
          <input type="file"  data-default-file="{{ $row->image ? asset('storage/' . $row->image) : '' }}" class="dropify" name="image" data-height="150" />
        </div>

         

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
