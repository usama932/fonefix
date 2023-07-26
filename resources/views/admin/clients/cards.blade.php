@if($user->cards->isNotEmpty())
<div class="card-datatable table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tr class="bg-light-info">
            <td>ID Card</td>
            <td>File</td>
            <td>Add to Jobsheet</td>
        </tr>
        @foreach($user->cards as $card)
            <tr>
                <td>{{ $card->card->name }}</td>
                <td><a href="{{ asset("uploads/$card->image")}}" target="_blank"><img  width="100" src="{{ asset("uploads/$card->image")}}" alt=""></a></td>
                <td>
                    <label class="checkbox  checkbox-success checkbox-lg">
                        <input type="checkbox" class="" value="1" name="old_card{{$card->id}}"/>
                        <span></span>
                    </label>
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endif
