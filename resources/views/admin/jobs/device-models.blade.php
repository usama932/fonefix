@if($device_models->isNotEmpty())
<select name="device_model" class="form-control req dev_model select-2" id="device_model">
    <option value="">Select Model</option>
    @foreach($device_models as $model)
        <option value="{{$model->id}}">{{$model->name}}</option>
    @endforeach
</select>
    @else
    <p class="text-danger">No Devices Found</p>
@endif

