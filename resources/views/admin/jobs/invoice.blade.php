@extends('admin.layouts.master')
@section('title',$title)
@section('content')
  <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader" kt-hidden-height="54" style="">
      <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
          <!--begin::Page Heading-->
          <div class="d-flex align-items-baseline flex-wrap mr-5">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard</h5>
            <!--end::Page Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Manage Jobs</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Invoice Detail
              </li>

            </ul>
            <!--end::Breadcrumb-->
          </div>
          <!--end::Page Heading-->
        </div>
        <!--end::Info-->
      </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
      <!--begin::Container-->
      <div class="container">
        <!--begin::Card-->
        <div class="card card-custom card-sticky" id="kt_page_sticky_card">
          <div class="card-header" style="">
            <div class="card-title">
              <h3 class="card-label">Invoice Detail
                <i class="mr-2"></i>
                </h3>

            </div>
            <div class="card-toolbar">
                <a href="{{ route('jobs.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                    <i class="ki ki-long-arrow-back icon-sm"></i>Back
                </a>
                <a href="#" class="btn recent-btn btn-light-success
              font-weight-bolder mr-2">
                    <i class="fa fa-clock icon-sm"></i>Recent Transactions
                </a>
                @if(!$user->invoice)
                    <div class="btn-group">
                        <a href="#"   id="kt_btn" page="1" class="btn btn-success submit font-weight-bolder">
                            <i class="fa fa-credit-card icon-sm"></i>Card</a>


                        <a href="#"   id="kt_btn" page="2" class="btn btn-danger submit font-weight-bolder">
                            <i class="fa fa-money-bill icon-sm"></i>Cash</a>

                        <a href="#"   id="kt_btn" page="3" class="btn btn-warning submit font-weight-bolder">
                            <i class="fa fa-address-card icon-sm"></i>Credit</a>






                    </div>
                @endif
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
              {{ Form::open([ 'route' => 'job-invoice-store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}

              <div class="row">

                  <table class="table">
                      <tr>
                          <th>User</th>
                          <td>{{$user->customer->name ?? ""}}</td>
                          <th>Email</th>
                          <td>{{$user->customer->email ?? ""}}</td>
                      </tr>

                      <tr>
                          <th>Phone</th>
                          <td>{{$user->customer->phone ?? ""}}</td>
                          <th>Date:</th>
                          <td >
                            @php
                                $timezone =  App\Models\BasicSetting::where('user_id',auth()->user()->id)->first();
                            @endphp
                              <span style="font-weight: 100">
									{{$user->created_at->timezone($timezone->timezone)->toDayDateTimeString()}}
								</span>
                          </td>
                      </tr>

                      <tr>
                          <td><b>Service type:</b></td>
                          <td>


                              @if($user->service_type == 1)
                                  Carry In
                              @elseif($user->service_type == 2)
                                  Pick Up
                              @elseif($user->service_type == 3)
                                  On Site
                              @else
                                  Courier
                              @endif
                          </td>
                          <td>
                              <b>
                                  Expected Delivery Date:
                              </b>
                          </td>
                          <td >


                              <span style="font-weight: 100">
                                {{$user->expected_delivery}}
                        </span>
                          </td>
                      </tr>

                      <tr>
                          <td><b>Job sheet number:</b></td>
                          <td>

                              #{{$user->job_sheet_number}}
                          </td>
                          <td><b>Brand:</b></td>
                          <td>
                              {{$user->brand->name}}</td>
                      </tr>

                      <tr>
                          <td><b>Device:</b></td>
                          <td>

                              @if($user->device->type ==1)
                                  Mobile Phones

                              @else
                                  Laptop

                              @endif
                          </td>
                          <td><b>Device Model:</b></td>
                          <td>
                              {{$user->device->name}}
                          </td>
                      </tr>

                      <tr>
                          <td>
                              <b>Serial Number:</b>
                          </td>
                          <td>
                              {{$user->serial_number}}
                          </td>
                          <td>   <b>Password:</b></td>
                          <td>
                              {{$user->password}}
                          </td>
                      </tr>

                      <tr>
                          <td >
                              <b>
                                  Invoice No.:
                              </b>
                          </td>
                          <td>
                              {{$user->id}}
                          </td>
                          <td >
                              <b>
                                  Estimated Cost:
                              </b>
                          </td>
                          <td>
                              <span class="display_currency" data-currency_symbol="true"> {{$user->cost}}</span>
                          </td>
                      </tr>

                      <tr>
                          <td >
                              <b>
                                  Status:
                              </b>
                          </td>
                          <td>
                              @if($user->status_id)
                                  {{$user->stat->name ?? ''}}

                              @else
                                  Nil
                              @endif
                          </td>
                          <td >
                              <b>
                                  Location:
                              </b>
                          </td>
                          <td>
                              {{$user->shop->name}}
                          </td>
                      </tr>


                      <tr>
                          <td >
                              <b>
                                  Pre Repair Checklist:
                              </b>
                          </td>
                          <td>
                              @foreach($user->preRepairs as $repair)
                                  <div class="col-xs-4">
                                      <i class="fas @if($repair->value == 'Yes') fa-check-square text-success @elseif($repair->value == "N/A" || $repair->value == "No") fa-window-close text-danger @else fa-square  @endif fa-lg"></i>
                                      {{$repair->name}}
                                      <br>
                                  </div>
                              @endforeach

                          </td>
                          <td>
                              <b>
                                  Product Configuration:
                              </b>
                          </td>
                          <td >

                              {{$user->product_configuration}}

                          </td>
                      </tr>

                      <tr>
                          <td><b>
                                  Condition Of The Product:
                              </b>
                          </td>
                          <td >

                              {{$user->condition_of_product}}
                          </td>
                          <th>Status</th>
                          <td>
                              @if($user->status == 1)
                                  <label class="label label-info label-inline mr-2">Accepted</label>
                              @elseif($user->status == 2)
                                  <label class="label label-primary label-inline mr-2">Progressing</label>
                              @elseif($user->status == 3)
                                  <label class="label label-success label-inline mr-2">Completed</label>
                              @endif
                          </td>
                      </tr>


                  </table>
                  <table class="table">
                      <thead>
                      <tr class="bg-light-info">
                          <th>Item Detail</th>
                          <th>Quantity</th>
                          <th>price</th>
                          <th>Amount</th>
                      </tr>
                      </thead>
                      <tbody>
                      @php
                          $ov_total = 0;
                          $sn = 0;
                      @endphp
                        @foreach($user->parts as $item)
{{--                            @if(!$item->invoice_id)--}}
                            <tr>
                                @php
                                    $sub_total = $item->amount * $item->quantity;
                                    $ov_total = $ov_total + $sub_total;
                                    $sn = $sn +1;
                                @endphp
                                <td>{{$item->description}}</td>

                                @if($item->is_regular == 1)
                                    <td>{{$item->quantity}}</td>
                                @else
                                    <td></td>
                                @endif

                                <td>{{$item->amount}}</td>
                                <td>${{round($item->amount * $item->quantity,2)}}</td>

                            </tr>
{{--                            @endif--}}
                        @endforeach
                      </tbody>
                  </table>
                  <table class="table">

                      <tr>
                          <th>Total</th>
                          <input type="hidden" name="total" id="total" value="{{round($ov_total,2)}}">
                          <input type="hidden" name="discount" id="discount" value="0">
                          <input type="hidden" name="discount_type" id="discount_type" value="1">
                          <input type="hidden" name="payment_type" id="payment_type" value="1">
                          <input type="hidden" name="sn" id="products" value="{{$sn}}">
                          <input type="hidden" name="job_id" id="job_id" value="{{$user->id}}">
                          <td>${{round($ov_total,2)}}</td>
                      </tr>
                      <tr>
                          <th>Discount <i class="fa fa-edit addDiscount btn btn-outline-primary  btn-sm mb-2"></i></th>

                          <td><span class="discount-html">${{ $invoice->discount ?? '0.00'}}.00</span> </td>
                      </tr>
                      <tr>
                          <th class="text-success">Payable Amount</th>
                          <td>$<span class="text-green payable-amount-html">{{round($ov_total,2)}}</span></td>
                      </tr>


                  </table>
                  @php
                      $invoice = \App\Models\Invoice::orderBy("id", "Desc")->where("job_id",$user->id)->first();
                      $transactions = \App\Models\Invoice::orderBy("id", "Desc")->where("job_id",$user->id)->get();
                  @endphp
                  @if($invoice)
                      <a href="{{route("invoice-pdf",$invoice->number)}}" class="invoice-pdf" style="display:none" target="_blank" id="invoice-pdf" >invoice</a>
                  @endif
              </div>
          {{Form::close()}}
            <!--end::Form-->
          </div>

            <!-- Modal-->
            <div class="modal fade" id="clientModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Discount</h4> </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="">Discount Type</label>
                                <select name="" class="form-control" id="pop-discount-type">
                                    <option value="1">Fixed</option>
                                    <option value="2">Percentage</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="">Discount Amount</label>
                                <input type="number" name="" id="pop-discount-amount" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="update-discount-amount" class="btn btn-primary font-weight-bold" data-dismiss="modal">Update</button>
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div> <!-- Modal-->
            <div class="modal fade" id="transactionModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Recent Transactions</h4> </div>
                        <div class="modal-body">
                            <table class="table table-striped table-bordered table-hover">
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$transaction->number}} ({{$user->customer->name}})</td>
                                    <td>
                                        @if($transaction->discount_type == 1)
                                             {{$transaction->total - $transaction->discount}}
                                            @php $res = $transaction->discount;
                                            @endphp
                                        @else
                                            @php $res = ( $transaction->total * $transaction->discount / 100 );

                                            @endphp
                                          {{$transaction->total - $res}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($transaction->payment_method == 1)
                                            Card
                                        @elseif($transaction->payment_method == 2)
                                            Cash
                                        @else
                                            Credit
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route("invoice-pdf",$transaction->number)}}"  target="_blank"  >
                                            <i class="fa flaticon2-printer  btn btn-outline-primary  btn-sm mb-2"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->

      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->
  </div>
@endsection
@section("scripts")
    <script !src="">
        $(".recent-btn").click(function () {
            $('#transactionModel').modal('show');
        });
        $(document).ready(function() {
            @if(Session::has('success_message'))
            setTimeout(function() {
                document.getElementById('invoice-pdf').click();
            },1000);
            @endif
        });
        $("body").on("click",".submit",function () {
            var page = $(this).attr("page");
            $("#payment_type").val(page);

            if($("#products").val() == 0){
                Swal.fire(
                    "Sorry!",
                    "There is no Product",
                    "error"
                );
            }else{
                // Swal.fire(
                //     "Deleted!",
                //     "Your Form has been submitted.",
                //     "success"
                // );
                $("#client_add_form").submit();
            }
        });
        $("#update-discount-amount").click(function(){
           var total  = parseInt($("#total").val());
           var type  = $("#pop-discount-type").val();
           var dis_value = parseInt($("#pop-discount-amount").val());
            $("#discount").val(dis_value);
            $("#discount_type").val(type);
           if (type == 1){
               $(".discount-html").html(dis_value.toFixed(2));
               var result = parseInt($("#total").val()) - dis_value;
               $(".payable-amount-html").html(result.toFixed(2));

           }else{
               var result_dis = ( total * dis_value / 100 );
               $(".discount-html").html(result_dis.toFixed(2));
               var result = parseInt($("#total").val()) - result_dis;
               $(".payable-amount-html").html(result.toFixed(2));
           }

        });
        $("#status").change(function () {
            $("#client_update_form").submit();
        });

        $(".addDiscount").click(function () {
            $('#clientModel').modal('show');
        });

        function printDiv()
        {

            var divToPrint=document.getElementById('DivIdToPrint');

            var htmlToPrint = '' +
                '<style type="text/css">' +
                'table th, table td {' +
                'border:1px solid #000;' +
                'padding;0.5em;' +
                '}' +
                '</style>';
            htmlToPrint += divToPrint.outerHTML;
            newWin = window.open("");
            // newWin.document.write("<h3 align='center'>Print Page</h3>");
            newWin.document.write(htmlToPrint);
            newWin.print();
            newWin.close();
        }


    </script>
@endsection
