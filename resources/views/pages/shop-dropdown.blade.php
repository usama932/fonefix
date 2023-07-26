<option value="">Select shops</option>
@foreach($shops as $shop)
    <option value="{{$shop->id}}">{{$shop->name}}({{$shop->address}})</option>
@endforeach