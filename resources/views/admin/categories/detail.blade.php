<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
			<td>{{$user->name}}</td>
		</tr>
        @if(auth()->user()->role == '1')
        <tr>
			<td>Shop</td>
			<td> {{ $category->shop->name ?? "Not Assign"}}</td>
		</tr>
        @endif
		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>

