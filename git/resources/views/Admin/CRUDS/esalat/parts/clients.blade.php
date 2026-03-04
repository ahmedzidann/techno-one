<div class="d-flex flex-column mb-7 fv-row col-sm-4">
    <!--begin::Label-->
    <label for="client_select" class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
        <span class="required mr-1"></span>
    </label>
    <!--end::Label-->
    <select class="form-control" id="client_select">
        <option selected disabled  >اختر  العميل</option>

        @foreach($clients as $client)

            <option value="{{$client->id}}">{{$client->name}}</option>

        @endforeach

    </select>
</div>
