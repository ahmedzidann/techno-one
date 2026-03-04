        <div class="card-body">
            <table id="table" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم العميل</th>
                        <th>رقم التليفون</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($representative->clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->phone }}</td>
                            <td> <button class="btn rounded-pill btn-danger waves-effect waves-light delete-client"
                                    data-client-id="{{ $client->id }}"
                                    data-representative-id="{{ $representative->id }}">
                                    <span class="svg-icon svg-icon-3">
                                        <span class="svg-icon svg-icon-3">
                                            <i class="fa fa-trash"></i>
                                        </span>
                                    </span>
                                </button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
