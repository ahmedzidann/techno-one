<!--begin::Form-->

<form id="form" enctype="multipart/form-data" method="POST" action="{{ route('esalat.store') }}">
    @csrf
    <div class="row g-4">

     <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="rkm_esal" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الايصال التسلسلي</span>
            </label>
            <!--end::Label-->
            <input id="rkm_esal" required type="text" readonly class="form-control form-control-solid" name="rkm_esal"
                value="{{ $lastEsal ? $lastEsal->rkm_esal + 1 : 1 }}" />
        </div>
  <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="rkm_esal" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> رقم الايصال الدفتري  </span>
            </label>
            <!--end::Label-->
            <input id="dafter_rkm_esal" required type="text"  class="form-control form-control-solid" name="dafter_rkm_esal"
                value="" />
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="date_esal" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1">تاريخ الايصال</span>
            </label>
            <!--end::Label-->
            <input id="date_esal" required type="date" class="form-control form-control-solid" name="date_esal"
                value="{{ date('Y-m-d') }}" />
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <label for="type" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> النوع</span>
            </label>
            <select name="type" class="form-control " id='type' style='width: 100%;'>
                <option selected disabled>اختر النوع</option>
                <option value="1">إيصال</option>
                <option value="2">شيك</option>
            </select>
        </div>
        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <label for="channel_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> العميل</span>
            </label>
            <select name="client_id" id='channel_id' style='width: 100%;'>
                <option value='0'>- Search Channel -</option>
            </select>
        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4">
            <!--begin::Label-->
            <label for="paid" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> المبلغ</span>
            </label>
            <!--end::Label-->
            <input id="paid" min="1" required type="number" class="form-control form-control-solid"
                name="paid" value="" />
        </div>
        
        <div class="d-flex flex-column mb-7 fv-row col-sm-4 " id="payment_category">

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4 " id="months_data">

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-4 " id="client_payment_setting">

        </div>
        <div class="row" id="cheque_data">

        </div>

        <div class="d-flex flex-column mb-7 fv-row col-sm-12">
            <!--begin::Label-->
            <label for="notes" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                <span class="required mr-1"> الملاحظات</span>
            </label>
            <!--end::Label-->
            <textarea id="notes" type="date" class="form-control form-control-solid" name="notes"></textarea>
        </div>


    </div>
</form>




<script>
    (function() {

        $("#channel_id").select2({
            placeholder: 'searching For Clients...',
            // width: '350px',
            allowClear: true,
            ajax: {
                url: '{{ route('admin.getClients') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: true
            }
        });
    })();
</script>

<script>
    $(document).ready(function() {
        $('#channel_id').on('change', function() {

            // Clear existing inputs to avoid duplication
            $('#payment_category').empty();
            $('#months_data').empty();
            $('#client_payment_setting').empty();

            let channelId = $(this).val();
            let url = `{{ route('clients.show', ['client' => ':id']) }}`;

            // Replace the placeholder with the actual channel ID
            url = url.replace(':id', channelId);

            $.ajax({
                url: url, // Your endpoint to fetch payment category
                type: 'GET',
                success: function(response) {
                    // Assuming response contains the payment category value
                    let paymentCategoryValue = response
                        .client.payment_category; // Adjust based on your response structure
                    let paymentLabel = response
                        .category; // Adjust based on your response structure

                    // Create new input fields with the fetched payment category
                    let paymentCategoryInput =
                        `<label for="payment_category" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1"> فئة سداد العميل</span>
                </label>
                <input id="payment_category" required type="text" class="form-control form-control-solid" data="${paymentCategoryValue}" value="${paymentLabel}" readonly/>
                <input id="payment_category"  type="hidden" class="form-control form-control-solid"  name="payment_category" value="${paymentCategoryValue}" />`;
                    $('#payment_category').append(paymentCategoryInput);
                    if (response.client.payment_category != 4) {
                        // Create months data input
                        let monthsDataInput = `<label for="month" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required mr-1"> الشهر</span>
                </label>
                <select id="payment_month" name="month" class="form-control">
                    <option selected disabled>اختر الشهر</option>
                    @for ($month = 1; $month <= 12; $month++)
                        <option value="{{ $month }}"> {{ $month }}</option>
                    @endfor
                </select>`;

                        // Append the new input fields

                        $('#months_data').append(monthsDataInput);
                    }

                    onChangeMonth(channelId);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // Handle error appropriately
                }
            });

        });
    });
</script>
<script>
    function onChangeMonth(clientId) {
        $('#payment_month').on('change', function() {
            let month = $(this).val();
            let url = `{{ route('admin.getClientPaymentSettings') }}`;

            // Send the data to the server
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    client_id: clientId,
                    month: month,
                    _token: '{{ csrf_token() }}' // Include CSRF token if needed
                },
                success: function(response) {
                    // Clear previous data from the div
                    $('#client_payment_setting').empty();

                    // Create a select element
                    var selectElement = `<label for="client_payment_setting_id" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                        <span class="required mr-1">اعدادات السداد</span>
                                    </label>
                                    <select id="client_payment_setting_id" name="client_payment_setting_id" class="form-control">
                                        <option selected disabled>اختر الخيار</option>`;

                    // Assuming response is an array of options, loop through and append options
                    response.data.forEach(function(option) {
                        selectElement +=
                            `<option value="${option.id}">${option.title}</option>`;
                    });

                    // Close the select tag
                    selectElement += `</select>`;

                    // Append the new select element to the div
                    $('#client_payment_setting').append(selectElement);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    // Handle any error cases here
                }
            });

        });
    }
</script>
