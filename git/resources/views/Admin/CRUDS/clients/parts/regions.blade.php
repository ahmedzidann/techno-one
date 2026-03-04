@if (count($regions) > 0)

    <option selected disabled>اختر المنطقة الان</option>
    @foreach ($regions as $region)
        <option value="{{ $region->id }}"> {{ $region->title }}</option>
    @endforeach
@else
    <option selected disabled>تلك المدينة ليس بها مناطق</option>



@endif
