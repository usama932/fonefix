<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Title</td>
			<td>{{$user->title}}</td>
		</tr>
		<tr>
			<td>Image</td>
			<td><img src="{{ asset("uploads/$user->image") }}" width="150" alt=""></td>
		</tr>


		</tbody>
	</table>
</div>

