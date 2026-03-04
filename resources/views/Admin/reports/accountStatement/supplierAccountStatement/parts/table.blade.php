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
        <th> المديونية السابقة</th>
        <th>0</th>
        <th>{{$supplier->previous_indebtedness}}</th>
        <th>{{$total_price=$supplier->previous_indebtedness}}</th>
    </tr>
    @foreach($rows as $key=>$row)

        @if($row->type=='purchases')
        <tr>
            <th>{{$key+2}}</th>
            <th>{{$row->date}}</th>
            <th>فواتير شراء</th>
            <th>{{$debt=$row->paid}}</th>
            <th>{{$credit=$row->total_price}}</th>
            <th>{{$total_price=$total_price+$credit-$debt}}</th>
        </tr>
        @elseif($row->type=='headBackPurchases')

            <tr>
                <th>{{$key+2}}</th>
                <th>{{$row->date}}</th>
                <th>مرتجع </th>
                <th>{{$debt=$row->total_price}}</th>
                <th>{{$credit=$row->paid}}</th>
                <th>{{$total_price=$total_price+$credit-$debt}}</th>
            </tr>


        @elseif($row->type=='voucher')

            <tr>
                <th>{{$key+2}}</th>
                <th>{{date('Y-m-d',$row->date)}}</th>
                <th> توريد ايصالات</th>
                <th>{{$debt=$row->total_price}}</th>
                <th>{{$credit=$row->paid}}</th>
                <th>{{$total_price=$total_price+$credit-$debt}}</th>
            </tr>
        @else

        @endif



            @endforeach
    </tbody>
</table>
