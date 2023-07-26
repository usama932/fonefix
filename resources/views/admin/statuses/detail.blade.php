<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
			<td>{{$user->name}}</td>
		</tr>
        <tr>
			<td>SMS Template</td>
			<td>{{$user->sms_template}}</td>
		</tr>
        <tr>
			<td>Whatsapp Template</td>
			<td>{{$user->whatsapp_template}}</td>
		</tr>
        <tr>
			<td>Email Body</td>
			<td>{{$user->email_body}}</td>
		</tr>
		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>

