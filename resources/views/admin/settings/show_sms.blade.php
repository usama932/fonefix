<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Name</td>
            <td>
            @if($user->type == '1')
                Pearl SMS

            @elseif($user->type == '2')

                Twillo

            @elseif($user->type == '3')

                Bulk SMS

        </td>
            @endif

		</tr>
        <tr>
			<td>Template</td>

			<td>{{$user->template->name}}</td>
		</tr>

		<tr>
			<td>Created at</td>
			<td>{{$user->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>

