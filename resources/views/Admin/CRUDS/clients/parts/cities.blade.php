@if(count($cities)>0)

    <option selected disabled>اختر المدينة الان</option>
    @foreach($cities as $city)
        <option value="{{$city->id}}"> {{$city->title}}</option>
    @endforeach


@else


    <option selected disabled>تلك الدولة ليس بها مدن</option>



@endif
