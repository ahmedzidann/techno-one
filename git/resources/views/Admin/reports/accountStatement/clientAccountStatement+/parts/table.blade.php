<table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
       style="width:100%">
    <thead>
    <tr>
        <th>المسلسل</th>
        <th> التاريخ </th>
        <th>العملية</th>
        <th> مدين</th>
        <th>دائن</th>
        <th> الرصيد</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th>1</th>
        <th></th>
        <th>رصيد اول مدة</th>
        <th>{{$client->previous_indebtedness}}</th>
        <th>0</th>
        <th>{{$total_price=$client->previous_indebtedness}}</th>
    </tr>
    @foreach($rows as $key=>$row)

        @if($row->type=='sales')
        <tr>
            <th>{{$key+2}}</th>
            <th>{{$row->date}}</th>
            <th>مبيعات</th>
            <th>{{$debt=$row->total_price}}</th>
            <th>{{$credit=$row->paid}}</th>
            <th>{{$total_price=$total_price+$debt-$credit}}</th>
        </tr>
        @elseif($row->type=='headBackSales')

            <tr>
                <th>{{$key+2}}</th>
                <th>{{$row->date}}</th>
                <th>مرتجع مبيعات</th>
                <th>{{$debt=$row->paid}}</th>
                <th>{{$credit=$row->total_price}}</th>
                <th>{{$total_price=$total_price+$debt-$credit}}</th>
            </tr>


        @elseif($row->type=='esalat')

            <tr>
                <th>{{$key+2}}</th>
                <th>{{$row->date}}</th>
                <th> تحصيل سند قبض</th>
                <th>{{$debt=$row->paid}}</th>
                <th>{{$credit=$row->total_price}}</th>
                <th>{{$total_price=$total_price+$debt-$credit}}</th>
            </tr>
        @else

        @endif



            @endforeach
    </tbody>
</table>
