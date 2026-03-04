<table id="table-details" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>الشركة</th>
            <th>الصنف</th>
            <th>كود الصنف</th>
            <th>سعر بيع الصنف</th>
            <th>البونص</th>
            <th>نسبة الخصم</th>
            <th>رقم التشغيل</th>
            <th>الكمية</th>
            <th>ملاحظات</th>
            <th>الاجمالي</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                <th>{{ $row->id }}</th>
                <th>{{ $row->company->title ?? '' }}</th>
                <th>{{ $row->productive->name ?? '' }}</th>
                <th>{{ $row->productive->code ?? '' }}</th>
                <th>{{ $row->productive_sale_price }}</th>
                <th>{{ $row->bouns }}</th>
                <th>{{ $row->discount_percentage }}</th>
                <th>{{ $row->batch_number }}</th>
                <th>{{ $row->amount }}</th>
                <th>{{ $row->notes }}</th>
                <th>{{ $row->total }}</th>

            </tr>
        @endforeach
    </tbody>
</table>
