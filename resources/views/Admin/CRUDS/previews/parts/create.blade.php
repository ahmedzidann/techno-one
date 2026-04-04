<!--begin::Form-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropify/0.2.2/css/dropify.min.css">
<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('previews.store') }}">
    @csrf
    <div class="row g-4">


        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">الاسم</span>
            </label>
            <!--end::Label-->
            <input id="name" required type="text" class="form-control form-control-solid" name="name"
                value="" />
        </div>

        
       
      

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="category_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">التصنيف </span>
            </label>

            <select id="preview_category_id" name="preview_category_id" class="form-control">
                <option selected disabled>اختر التصنيف</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"> {{ $category->name }}</option>
                @endforeach
            </select>

        </div>



        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="name" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">النقاط</span>
            </label>
            <!--end::Label-->
            <input id="points" required type="number" class="form-control form-control-solid" name="points"
                value="" />
        </div>

       

        <div class="d-flex flex-column mb-7 fv-row col-sm-12 ">
            <label for="zone_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الصوره</span>
            </label>
          <input type="file" class="dropify" name="image" data-height="150" />
        </div>

        

    </div>
</form>

<script>
$(document).on('change', '#zone_id', function () {
    let parentId = $(this).val(); // Get the selected value from the parent select
    if (parentId) {
        $.ajax({
            url: '{{ route("admin.getChildCities") }}', // Replace with your route
            type: 'GET',
            data: { zone_id: parentId }, // Send the parent_id as a query parameter
            success: function (response) {
                let $childSelect = $('#city_id'); // Target the child select element
                $childSelect.empty(); // Clear previous options
                $childSelect.append('<option value="">اختر المدينة</option>'); // Add a placeholder
                
                // Populate new options
                $.each(response.data, function (index, item) {
                    $childSelect.append('<option value="' + item.id + '">' + item.title + '</option>');
                });
            },
            error: function (xhr) {
                toastr.error("Error fetching data:", xhr.responseText);
            }
        });
    } else {
        $('#city_id').empty().append('<option value="">اختر المدينة</option>'); // Reset if no parent selected
    }
});

</script>


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



