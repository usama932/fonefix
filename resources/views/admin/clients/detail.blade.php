<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
			<td>{{$user->name}}</td>
		</tr>
		<tr>
			<td>Email</td>
			<td>{{$user->email}}</td>
		</tr>
		<tr>
			<td>Phone</td>
			<td>{{$user->phone}}</td>
		</tr>
		<tr>
			<td>Alternative Phone</td>
			<td>{{$user->alternative_phone}}</td>
		</tr>
		<tr>
			<td>Address Line 1</td>
			<td>{{$user->line1}}</td>
		</tr>
		<tr>
			<td>Address Line 2</td>
			<td>{{$user->line2}}</td>
		</tr>
		<tr>
			<td>City</td>
			<td>{{$user->city}}</td>
		</tr>
		<tr>
			<td>PostalCode</td>
			<td>{{$user->postal_code}}</td>
		</tr>
		<tr>
			<td>Location</td>
			<td>{{$user->location}}</td>
		</tr>
		<tr>
			<td>Province</td>
			<td>@if($user->province_id){{$user->province->name}}@endif</td>
		</tr>
		<tr>
			<td>Country</td>
			<td>@if($user->country_id){{$user->country->name}}@endif</td>
		</tr>
		<tr>
			<td>Status</td>
			<td>
				@if($user->active)
					<label class="label label-success label-inline mr-2">Active</label>
				@else
					<label class="label label-danger label-inline mr-2">Inactive</label>
				@endif
			</td>
		</tr>
		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-light-info">
            <td>ID Card</td>
            <td>File</td>
        </tr>
        @foreach($user->cards as $card)
            <tr>
                <td>{{ $card->card->name }}</td>
                <td><a href="{{ asset("uploads/$card->image")}}" target="_blank"><img  width="100" src="{{ asset("uploads/$card->image")}}" alt=""></a></td>
            </tr>
        @endforeach
        <table class="table table-striped mt-5">
            <tr class="bg-light-info">
                <td>Device</td>
                <td>Brand</td>
                <td>Models</td>
            </tr>
            @foreach($user->brands as $brand)
                <tr>
                    <td>
                        @if($brand->device == 1)
                            Mobile
                        @else
                            Laptop
                        @endif
                    </td>
                    <td>
                        {{$brand->brand->name}}
                    </td>
                    <td>
                        @foreach($brand->devices as $device)
                            {{$device->device->name}},
                        @endforeach
                    </td>

                </tr>
            @endforeach
        </table>
    </table>
</div>

