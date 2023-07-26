<div class="card-datatable table-responsive">
    <table id="clients" class="datatables-demo table table-striped table-bordered">
        <tbody>
{{--        <tr>--}}
{{--            <td>Job Sheet Number</td>--}}
{{--            <td>{{$user->job_sheet_number}}</td>--}}
{{--        </tr>--}}


        </tbody>
    </table>
    <form action="{{route("update-enquiry-status")}}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{$user->id}}">
        <div class="row">
            <div class="col-md-6 form-group ">
                <div class="form-group  {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label class="">
                        Completed</label>
                    <div class="">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" {{ ($user->status) ?'checked':'' }} name="status" value="1">
                            <span></span>
                          </label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group ">
                <div class="form-group  ">
                    <label class="">
                        Estimate Date</label>
                    <div class="">
                        {{ Form::date('estimate_date', $user->estimate_date, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('estimate_date') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-12 form-group ">
                <div class="form-group  {{ $errors->has('message') ? 'has-error' : '' }}">
                    <label class="">
                        Message</label>
                    <div class="">
                        {{ Form::textarea('message', $user->message, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('message') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group ">
                <div class="form-group  {{ $errors->has('sms') ? 'has-error' : '' }}">
                    <label class="">
                        Send notification SMS</label>
                    <div class="">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" {{ ($user->sms) ?'checked':'' }} name="sms" value="1">
                            <span></span>
                          </label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group ">
                <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label class="">
                        Send notification Email</label>
                    <div class="">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" {{ ($user->email) ?'checked':'' }} name="email" value="1">
                            <span></span>
                          </label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 form-group ">
                <div class="form-group  ">
                    <input type="submit" value="Update" class="btn btn-success float-right">
                </div>
            </div>
        </div>
    </form>
</div>

