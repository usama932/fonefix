@if($provinces->isNotEmpty())
    <select name="province" id="province" class="form-control province">
        @foreach($provinces as $province)
            @php $old_province = \App\Models\UserProvince::where([['province_id', $province->id],['user_id',Auth::id()]])->first(); @endphp
            @if($old_province)
                <option value="{{$province->id}}">{{$province->name}}</option>
            @endif
        @endforeach
    </select>
@else
    <span class="text-danger">No Provinces</span>
@endif
