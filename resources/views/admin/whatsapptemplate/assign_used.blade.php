<div class="card-datatable table-responsive">
    <form class="form" method="post" action=" {{route('admin.wassign_used')}}">
        @csrf
        <div class="card-body">
            <input type="hidden" value="{{$template->id  }}" name="id">

            <div class="form-group row">
                <label class="col-3 col-form-label">Used</label>
                <div class="col-3">
                       <span class="switch switch-outline switch-icon switch-success">
                        <label><input type="checkbox"@if($template->used) @if($template->used->used)  checked="checked" @endif @endif id="used" name="used" value="1">
                          <span></span>
                        </label>
                      </span>
                </div>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>
