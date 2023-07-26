<div class="card-datatable table-responsive">
    <form class="form" method="post" action=" {{route('admin.payamount')}}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <input type="hidden" value="{{$user->id}}" name="user_id">
                <label>Full Name:</label>
                <input type="text" class="form-control form-control-solid" placeholder="{{$user->name}}"/ readonly>
                
            </div>
            <div class="form-group">
                <label>Total Pending</label>
                <div class="input-group input-group-lg">
                
                    <input type="text" name="real_amount" class="form-control form-control-solid" value="{{$user->pendingInvoices->sum('total')}}" readonly>
                </div>
            </div>
            <div class="form-group">
                <label>Pay Amount</label>
                <div class="input-group input-group-lg">
                  
                    <input type="number" name="pay_amount" class="form-control form-control-solid" value="{{$user->pendingInvoices->sum('total')}}"/>
                  
                </div>
                <span class="form-text text-muted">Please Enter Valid Amount Not Greater than Total</span>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="card" value="2" id="flexRadioDefault1">
                <label class="form-check-label" for="flexRadioDefault1">
                    Cash
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="card" value="1" id="flexRadioDefault2" checked>
                <label class="form-check-label" for="flexRadioDefault2">
                 Card
                </label>
              </div>
           
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary mr-2">Submit</button>
            <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </form>
</div>

