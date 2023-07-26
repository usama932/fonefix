<div class="card-datatable table-responsive">
    <table id="users" class="datatables-demo table table-striped table-bordered">
        <tbody>
        <tr>
            <td>Vehicle #</td>
            <td>{{$vehicles->vehicle_no}}</td>
        </tr>
        <tr>
            <td>Registration</td>
            <td>{{$vehicles->Registration_no}}</td>
        </tr>        <tr>
            <td>Notes</td>
            <td>{!! $vehicles->notes !!}</td>
        </tr>

        <tr>
            <td>Status</td>
            <td>
                @if($vehicles->active == 1)
                    <label class="label label-success label-inline mr-2">Active</label>
                @else
                    <label class="label label-danger label-inline mr-2">Inactive</label>
                @endif
            </td>
        </tr>
        <tr>
            <td>Year</td>
            <td>{{$vehicles->year}}</td>
        </tr>
        <tr>
            <td>Make</td>
            <td>{{$vehicles->make}}</td>
        </tr>
        <tr>
            <td>Model</td>
            <td>{{$vehicles->model}}</td>
        </tr>
        <tr>
            <td>Image</td>
            <td><img src="{{asset('uploads/'.$vehicles->image)}}"  style="width:100%;" alt="Image is not found." /></td>
        </tr>
        <tr>
            <td>Created at</td>
            <td>{{$vehicles->created_at}}</td>
        </tr>

        </tbody>
    </table>
    @if(!empty($vehicles->tickets))
    <h3>Tickets: </h3>
        <table id="users" class="datatables-demo table table-striped table-bordered">

                <tr>
                    <th>Remarks</th>
                    <th>Complaint</th>
                    <th>Vehcile Milage</th>
                    <th>Status</th>

                </tr>

            <tbody>
                @foreach($vehicles->tickets as $ticket)
                <tr>
                    <td>{!!  $ticket->remarks !!}</td>
                    <td>{{  $ticket->complaint}}</td>
                    <td>{{  $ticket->vehicle_mileage }}</td>
                    <td>
                        @if($ticket->status == '1')
                            <label class="label label-success label-inline mr-2">Active</label>
                        @else
                            <label class="label label-danger label-inline mr-2">Inactive</label>
                        @endif
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>

