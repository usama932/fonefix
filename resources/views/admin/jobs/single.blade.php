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
                Job  Detail
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
              <h3 class="card-label">Job Detail
                <i class="mr-2"></i>
                </h3>

            </div>
            <div class="card-toolbar">
                <a href="{{ route('jobs.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                    <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>
                <a href="#" onclick="printDiv();" class="btn btn-light-info
              font-weight-bolder mr-2">
                    <i class="fa fa-print icon-sm"></i>Print</a>
                <a href="{{ route('job-pdf',$user->id) }}" class="btn btn-light-success
              font-weight-bolder mr-2">
                    <i class="fa fa-file-pdf icon-sm"></i>Pdf</a>
            </div>
          </div>
          <div class="card-body" id="DivIdToPrint">
          @include('admin.partials._messages')
          <!--begin::Form-->
              <table class="table">
                  <tr>
                      <td>
                          @php $shop_logo = $user->shop->basicSetting->image; @endphp
                          <img src="{{asset("uploads/$shop_logo")}}" alt="Logo" style="width: auto; max-height: 90px; margin: auto;">
                      </td>
                      <td>
                          <p style="text-align: center;padding-top: 40px;padding-left: 110px;">
                              <strong class="font-23">
                                  {{$user->shop->basicSetting->name}}
                              </strong>
                              <br>
                              {{--                          Fonefix<br>--}}
                              <span>
                                 {!! $user->shop->basicSetting->address !!}
                                </span>
                              <br>
                              <span>
                                 {!! $user->shop->basicSetting->phone !!}
                                </span><br>
                              <span>
                                 {!! $user->shop->basicSetting->email !!}
                                </span>
                          </p>
                      </td>
                  </tr>
              </table>

              <table class="table table-bordered" style="margin-top: 15px;">
                  <tbody><tr>
                      <th rowspan="3">
                          Date:
                          @php
                          $timezone =  App\Models\BasicSetting::where('user_id',auth()->user()->id)->first();
                      @endphp

                          <span style="font-weight: 100">
									{{$user->created_at->timezone($timezone->timezone)->toDayDateTimeString()}}
								</span>
                      </th>
                  </tr>
                  <tr>
                      <td>
                          <b>Service type:</b>

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
                      <th rowspan="2">
                          <b>
                              Expected Delivery Date:
                          </b>
                          <span style="font-weight: 100">
										{{$user->expected_delivery}}
                        </span>
                      </th>
                  </tr>
                  <tr>
                      <td>
                          <b>Job sheet number:</b>
                          #{{$user->job_sheet_number}}
                      </td>
                  </tr>
                  <tr>
                      <td colspan="2">
                          <strong>Customer:</strong><br>
                          <p>
                              {{$user->customer->name}}

                              <br>Mobile: {{$user->customer->phone}}

                          </p>
                      </td>
                      <td>
                          <b>Brand:</b>
                          {{$user->brand->name}}
                          <br>
                          <b>Device:</b>
                          @if($user->device->type ==1)
                              Mobile Phones

                          @else
                              Laptop

                          @endif
                          <br>
                          <b>Device Model:</b>
                          {{$user->device->name}}
                          <br>
                          <b>Serial Number:</b>
                          {{$user->serial_number}}
                          <br>
                          <b>Password:</b>
                          {{$user->password}}
                          <br>
{{--                          <b>--}}
{{--                              Security Pattern code:--}}
{{--                          </b>--}}
{{--                          158963--}}
                      </td>
                  </tr>
                  @if ($user->invoice)
                  <tr>
                      <td colspan="2">
                          <b>
                              Invoice No.:
                          </b>
                      </td>
                      <td>
                          {{$user->invoice->number}}
                      </td>
                  </tr>
                  @endif
                  <tr>
                      <td colspan="2">
                          <b>
                              Estimated Cost:
                          </b>
                      </td>
                      <td>
                          <span class="display_currency" data-currency_symbol="true"> {{$user->cost}}</span>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="2">
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
                  </tr>
                  <tr>
                      <td colspan="2">
                          <b>
                              Location:
                          </b>
                      </td>
                      <td>
                          {{$user->shop->name}}
                      </td>
                  </tr>
{{--                  <tr>--}}
{{--                      <td colspan="2">--}}
{{--                          <b>--}}
{{--                              Technician:--}}
{{--                          </b>--}}
{{--                      </td>--}}
{{--                      <td>--}}

{{--                      </td>--}}
{{--                  </tr>--}}
                  <tr>
                      <td colspan="2">
                          <b>
                              Pre Repair Checklist:
                          </b>
                      </td>
                      <td>
                          @foreach($user->preRepairs as $repair)
                          <div class="col-xs-4">
                              <i class="fas @if($repair->value == "Yes") fa-check-square text-success @elseif( $repair->value == "No") fa-window-close text-danger @else fa-square  @endif fa-lg"></i>
                              {{$repair->name}}
                              <br>
                          </div>
                          @endforeach

                      </td>
                  </tr>
                  <tr>
                      <td colspan="3">
                          <b>
                              Product Configuration:
                          </b>
                          {{$user->product_configuration}}
                          <br>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="3">
                          <b>
                              Condition Of The Product:
                          </b> <br>
                          {{$user->condition_of_product}}
                      </td>
                  </tr>


                  <tr>
                      <th colspan="2">Parts used:</th>
                      <td>
                          <table>
                              <tbody>
                                @foreach($user->parts as $part)
                                  <tr>
                                      <td>{{$part->description}}: &nbsp;</td>
                                      <td>{{$part->quantity}} Pc(s)</td>
                                  </tr>
                                  @endforeach

                              </tbody>
                          </table>
                      </td>
                  </tr>
                  <tr>
                      <td colspan="3">
                          <b>
                              Problem Reported By The Customer:
                          </b> <br>
                          {{$user->problem_by_customer}}
                      </td>
                  </tr>
{{--                  <tr>--}}
{{--                      <td colspan="3">--}}
{{--                          <strong>--}}
{{--                              Terms &amp; Conditions:--}}
{{--                          </strong>--}}
{{--                      </td>--}}
{{--                  </tr>--}}
                  @foreach($user->cards as $card)
                      @if($card->use)
                      <tr>
                          <td colspan="2"><b>{{ $card->card->name }}</b></td>
                          <td><a href="{{ asset("uploads/$card->image")}}" target="_blank"><img  width="100" src="{{ asset("uploads/$card->image")}}" alt=""></a></td>
                      </tr>
                      @endif
                  @endforeach
                  <tr>
                      <td colspan="2">
                          <b>
                              Customer signature:
                          </b>
                      </td>
                      <td>
                          <b>
                              Authorized signature:
                          </b>
                      </td>
                  </tr>
                  </tbody>
              </table>
            <!--end::Form-->
          </div>

            <!-- Modal-->
            <div class="modal fade" id="clientModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="myModalLabel">Kid Detail</h4> </div>
                        <div class="modal-body"></div>
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
@section("stylesheets")
    <style>
        /*table th, table td{*/
        /*    border:1px solid #000;*/
        /*}*/
    </style>
    @endsection
@section("scripts")
    <script !src="">
        $("#status").change(function () {
            $("#client_update_form").submit();
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
                'table  {' +
                'width:100%;' +
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
