<table id="table-details" class="table table-bordered dt-responsive nowrap table-striped align-middle"
       style="width:100%">
    <thead>
    <tr>
        <th>#</th>
        <th>اسم الصنف</th>
        <th>كود الصنف</th>
        <th>  الوحدة</th>
        <th>الكمية</th>
        <th>سعر الشراء</th>

    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)

        <tr>
            <th>{{$row->id}}</th>
            <th>{{$row->productive->name??''}}</th>
            <th>{{$row->productive_code}}</th>
            <th>{{$row->productive->unit->title??''}}</th>
            <th>{{$row->amount}}</th>
            <th>{{$row->productive_buy_price}}</th>

        </tr>
    @endforeach
    </tbody>
</table>
