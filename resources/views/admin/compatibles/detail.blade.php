<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
			<td>{{$user->name}}</td>
		</tr>

		<tr>
			<td>Compatible With</td>
			<td>
                @foreach ($user->devices as $compatible)
                    {{$compatible->device->name}}/
                @endforeach
            </td>
		</tr>

		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>

