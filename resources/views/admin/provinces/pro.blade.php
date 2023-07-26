@foreach ($provinces as $province)
    <div class="form-group row">
        <div class="col-1"></div>
        <label class="col-4 col-form-label">{{$province->name}}</label>
        <div class="col-3">
             <span class="switch switch-outline switch-icon switch-success">
                 @php $old_province = \App\Models\UserProvince::where([['province_id', $province->id],['user_id',Auth::id()]])->first(); @endphp
              <label><input type="checkbox" @if($old_province) checked="checked"  @endif class="" name="province{{ $province->id}}" value="1">
                <span></span>
              </label>
            </span>
        </div>
    </div>
@endforeach
