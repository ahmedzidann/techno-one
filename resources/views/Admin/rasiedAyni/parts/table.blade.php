@foreach($productive->credit as $pivot)
    <tr id="tr-{{$pivot->id}}">
        <td>{{$pivot->id}}</td>
        <td>{{$pivot->productive->name??''}}</td>
        <td>{{$pivot->branch->title??''}}</td>
        <td>{{$pivot->storage->title??''}}</td>
        <td>{{$pivot->amount}}</td>
        <td>

            <button   class="btn rounded-pill btn-danger waves-effect waves-light deleteCredit"
            data-id="{{$pivot->id}}">
            <span class="svg-icon svg-icon-3">
                                <span class="svg-icon svg-icon-3">
                                    <i class="fa fa-trash"></i>
                                </span>
                            </span>
            </button>

        </td>

    </tr>
@endforeach
