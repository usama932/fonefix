<div class="card-datatable table-responsive">
	<table id="users" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
			<td>{{$user->name}}</td>
		</tr>

		<tr>
			<td>Documents</td>
			<td>
					<label class="label label-success label-inline mr-2">{{$user->documents->count()}}  Documents</label>

			</td>
		</tr>
		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>

