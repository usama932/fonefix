<div class="card-datatable table-responsive">
	<table id="clients" class="datatables-demo table table-striped table-bordered">
		<tbody>
		<tr>
			<td>Title</td>
			<td>{{$template->name}}</td>
		</tr>

        <tr>
			<td>SMS Template</td>
			<td>{{$template->description}}</td>
		</tr>
        @if($template->user_id == auth()->user()->id)
        <tr>
			<td>SMS P-ID</td>
			<td>{{$template->sms_peid}}</td>
		</tr>
        <tr>
			<td>SMS Type</td>
			<td>{{$template->sms_type}}</td>
		</tr>
        <tr>
			<td>SMS Template Id</td>
			<td>{{$template->sms_template_id}}</td>
		</tr>
        @endauth
        <tr>
			<td>SMS Template for</td>
			<td>
                @if($template->type == '1')
                    Invoice
                @elseif($template->type == '2')
                    Welcome Client
                @elseif($template->type == '3')
                    Enquiries
                @else
                    Not Available
                @endif
            </td>
		</tr>
        <tr>
			<td>Shared</td>
			<td>
                @if($template->shared == 1)
                    Yes
                @else
                    No
                @endif

            </td>
		</tr>
		<tr>
			<td>Created at</td>
			<td>{{$template->created_at}}</td>
		</tr>

		</tbody>
	</table>
</div>
