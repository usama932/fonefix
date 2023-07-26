<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
			<td>{{$user->name}}</td>
		</tr>
		<tr>
			<td>Sku</td>
			<td>{{$user->sku}}</td>
		</tr>
		<tr>
			<td>Price</td>
			<td>{{$user->price}}</td>
		</tr>
		<tr>
			<td>Description</td>
			<td>{{$user->description}}</td>
		</tr>
        @if($user->image)
            <tr>
                <td>Image</td>
                <td><img src="{{asset("uploads/$user->image")}}" alt="" width="200" class="mt-5"></td>
            </tr>
        @endif
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
            <td>Manage Stock</td>
            <td>
                @if($user->manage_stock)
                    <label class="label label-success label-inline mr-2">Yes</label>
                @else
                    <label class="label label-danger label-inline mr-2">No</label>
                @endif
            </td>
        </tr>
        @if($user->manage_stock)
        <tr>
            <td>Quantity</td>
            <td>{{$user->quantity}}</td>
        </tr>

       @endif
		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>

