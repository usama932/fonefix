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
                <a href="" class="text-muted">Manage Invoices</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Invoice
              </li>
              <li class="breadcrumb-item text-muted">
                  @if($user->job_id)
                      {{$user->job->customer->name}}
                  @else
                      {{$user->customer->name}}
                  @endif
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
              <h3 class="card-label">Invoice
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('invoices.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{route("invoice-pdf",$user->number)}}" target="_blank" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-print icon-sm"></i>Print</a>



              </div>
            </div>
          </div>
          <div class="card-body">
              <!-- table Head -->
              <table style="border-collapse: collapse;border-spacing: 0;margin-top: 20px;margin-bottom: 10px;margin-right: 10px;display: inline-table;width: 100%;">
                  <tbody>
                  @if($user->shop->basicSetting)
                      <tr>
                          <td align="center" style="text-align:center;padding: 0;font-size: 40px;">
                              @if($user->job_id)
                                  @php $logo = $user->job->shop->basicSetting->image @endphp
                                  <img src="{{asset("uploads/$logo")}}" width="150" alt="">
                              @else
                                  @php $logo = $user->shop->basicSetting->image@endphp
                                  <img src="{{asset("uploads/$logo")}}" width="150" alt="">
                              @endif
                          </td>
                      </tr>
                      <tr>
                          <td align="center" style="text-align:center;padding: 0;font-size: 40px;">
                              @if($user->job_id)
                                  {{$user->job->shop->basicSetting->name}}
                              @else
                                  {{$user->shop->basicSetting->name}}
                              @endif
                          </td>
                      </tr>

                      <tr>
                          <td align="center" style="text-align:center;padding: 0;">
                              @if($user->job_id)
                                  {!! $user->job->shop->basicSetting->address !!}
                              @else
                                  {!! $user->shop->basicSetting->address !!}
                              @endif
                          </td>
                      </tr>

                        <tr>
                            <td align="center" style="text-align:center;padding: 0;">
                                @if($user->job_id)
                                    {!! $user->job->shop->basicSetting->phone !!}
                                @else
                                    {!! $user->shop->basicSetting->phone !!}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td align="center" style="text-align:center;padding: 0;">
                                @if($user->job_id)
                                    {!! $user->job->shop->basicSetting->email !!}
                                @else
                                    {!! $user->shop->basicSetting->email !!}
                                @endif
                            </td>
                        </tr>



                  @endif

                  </tbody>
              </table>

              <!-- table 1 -->
              <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
                  <tbody>
                  <tr>
                      <td colspan="2" align="center" style="text-align:center;padding: 0;font-size: 32px;">
                          Invoice
                      </td>
                  </tr>
                  <tr>
                      <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;">
                          <strong>Invoice No.</strong> {{$user->number}}
                      </td>
                      <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;">
                          <strong>Date</strong> {{date('Y-m-d',strtotime($user->created_at))}}
                      </td>
                  </tr>
                  <tr>
                      <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;">
                          <strong>Customer</strong>:

                          @if($user->job_id)
                              {{$user->job->customer->name}}
                          @else
                              {{$user->customer->name}}
                          @endif
                      </td>
                  </tr>
                  <tr>
                      <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;">
                          <strong>Mobile</strong>: @if($user->job_id)
                              {{$user->job->customer->phone}}
                          @else
                              {{$user->customer->phone}}
                          @endif
                      </td>
                  </tr>
                  </tbody>
              </table>

              <!-- table 2 -->
              <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
                  <thead>
                  <tr>
                      <th align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 40%;">
                          Product
                      </th>
                      <th align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 20%;">
                          Quantity
                      </th>
{{--                      <th align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 20%;">--}}
{{--                          Unit Price--}}
{{--                      </th>--}}
                      <th align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 20%;">
                          Subtotal
                      </th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($user->parts as $part)
                      <tr>
                          <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">
                              {{$part->description}}
                          </td>
                          <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">
                              {{$part->quantity}} Pc(s)
                          </td>
{{--                          <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">--}}
{{--                              {{$part->amount}}--}}
{{--                          </td>--}}
                          <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">
                              {{$part->amount * $part->quantity}}
                          </td>
                      </tr>
                  @endforeach

                  </tbody>
              </table>

              <!-- table 3 -->
              <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
                  <tbody>
                  <tr>
                      <td valign="top" align="left" style="text-align: left;padding: 0;font-size: 16px;border-top: 1px solid #000000;width: 50%;">
                          <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
                              <tbody>
                              <tr>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 33.33%;">
                                      @if($user->payment_method == 1)
                                          Cash
                                      @elseif($user->payment_method == 2)
                                          Card
                                      @else
                                          Credit
                                      @endif
                                  </td>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 33.33%;">
                                      @if($user->discount_type == 1)
                                          ₴ {{$user->total - $user->discount}}
                                          @php $res = $user->discount;
                                          @endphp
                                      @else
                                          @php $res = ( $user->total * $user->discount / 100 );

                                          @endphp
                                          ₴ {{$user->total - $res}}
                                      @endif
                                  </td>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 33.33%;">
                                      {{date('Y-m-d',strtotime($user->created_at))}}
                                  </td>
                              </tr>
                              <tr>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 33.33%;">
                                      <strong>Total Paid</strong>
                                  </td>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 33.33%;">
                                      @if($user->discount_type == 1)
                                          ₴ {{$user->total - $user->discount}}
                                          @php $res = $user->discount;
                                          @endphp
                                      @else
                                          @php $res = ( $user->total * $user->discount / 100 );

                                          @endphp
                                          ₴ {{$user->total - $res}}
                                      @endif
                                  </td>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 33.33%;">

                                  </td>
                              </tr>
                              </tbody>
                          </table>
                      </td>
                      <td valign="top" align="left" style="text-align: left;padding: 0;font-size: 16px;border-top: 1px solid #000000;width: 50%;">
                          <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
                              <tbody>
                              <tr>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 50%;">
                                      <strong>Subtotal:</strong>
                                  </td>
                                  <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 50%;">
                                      ₴ {{$user->total}}
                                  </td>
                              </tr>
                              <tr>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                                      <strong>Discount @if($user->discount_type == 2)(₴ {{$user->discount}}%) @endif:</strong>
                                  </td>
                                  <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                                      (-) ₴ {{$res}}
                                  </td>
                              </tr>
                              <tr>
                                  <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                                      <strong>Total:</strong>
                                  </td>
                                  <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                                      @if($user->discount_type == 1)
                                          ₴ {{$user->total - $user->discount}}
                                          @php $res = $user->discount;
                                          @endphp
                                      @else
                                          @php $res = ( $user->total * $user->discount / 100 );

                                          @endphp
                                          ₴ {{$user->total - $res}}
                                      @endif
                                  </td>
                              </tr>
                              </tbody>
                          </table>
                      </td>
                  </tr>
                  </tbody>
              </table>
            <!--end::Form-->
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
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        table, th, td {
            padding: 7px 10px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.css" integrity="sha512-HcfKB3Y0Dvf+k1XOwAD6d0LXRFpCnwsapllBQIvvLtO2KMTa0nI5MtuTv3DuawpsiA0ztTeu690DnMux/SuXJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js" integrity="sha512-94dgCw8xWrVcgkmOc2fwKjO4dqy/X3q7IjFru6MHJKeaAzCvhkVtOS6S+co+RbcZvvPBngLzuVMApmxkuWZGwQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function () {
            $('#cp1').colorpicker();
        });
    </script>
    <script !src="">
        $(".summernote").summernote();
    </script>
@endsection
