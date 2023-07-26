<div class="card-datatable table-responsive">
	<table id="users" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
			<td>{{$user->name}}</td>
		</tr>

		<tr>
			<td>Description</td>
			<td>{{$user->description}}</td>
		</tr>

		<tr>
			<td>Folder</td>
			<td>
					<label class="label label-success label-inline mr-2">{{$user->folder->name}}</label>

			</td>
		</tr>
		<tr>
			<td>Document</td>
			<td><a href="{{asset("uploads/$user->document")}}">{{$user->document}}</a></td>
		</tr>
		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>

