@if($device->pre_repair)
    @php $pre_repair = explode('|',$device->pre_repair); @endphp
    <div class="row">
    @foreach($pre_repair as $pre)

        <div class="col-md-4 form-group ">
            <div class="form-group  ">
                <label >{{$pre}}</label>
                <div class="custom_radio_container">
                    <div class="custom_radio_group">
                        <input class="custom_radio_input" type="radio" name="pre_repair{{$loop->iteration}}" id="{{$pre}}_yes" value="Yes">
                        <label class="custom_radio_label" for="{{$pre}}_yes">
                            <img alt="" src="{{asset("assets/yes.png")}}" width="14"/>
                        </label>
                    </div>
                    <div class="custom_radio_group">
                        <input class="custom_radio_input" type="radio" name="pre_repair{{$loop->iteration}}" id="{{$pre}}_no" value="No">
                        <label class="custom_radio_label" for="{{$pre}}_no">
                            <img alt="" src="{{asset("assets/no.png")}}" width="14"/>
                        </label>
                    </div>
                    <div class="custom_radio_group">
                        <input class="custom_radio_input" type="radio" name="pre_repair{{$loop->iteration}}" id="{{$pre}}_na" value="N/A" checked>
                        <label class="custom_radio_label" for="{{$pre}}_na">N/A</label>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    </div>
@else
    <p class="text-danger">No Pre Repair Found</p>
@endif

