@if(count($storages)>0)

    <option selected disabled>اختر المخزن الان</option>
    @foreach($storages as $storage)
        <option value="{{$storage->id}}"> {{$storage->title}}</option>
    @endforeach


@else


    <option selected disabled>  تلك الفرع ليس لدية مخازن</option>




@endif
