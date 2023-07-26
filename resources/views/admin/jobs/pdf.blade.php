<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Pdf</title>
    <link rel="canonical" href="https://keenthemes.com/metronic"/>
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"/>
    <!--end::Fonts-->
    <style>
        table {
            width: 100%;
        }

        table th, table td {
            border: 1px solid;
            padding: 3px;
        }
    </style>
    <!--end::Layout Themes-->
</head>
<body>
<table>
    <tr>
        <td>
            <img src="{{$logo}}" alt="BTS" style="width: auto; max-height: 90px; margin: auto;">
        </td>
        <td>
            <p style="text-align: center;padding-top: 40px;padding-left: 110px;">
                @if($user->shop->basicSetting)
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
                                </span>
                @endif
            </p>
        </td>
    </tr>
</table>

<table class="table table-bordered" style="margin-top: 15px;">
    <tbody>
    <tr>
        <th rowspan="3">
            Date:
            <span style="font-weight: 100">
                @php
                $timezone =  App\Models\BasicSetting::where('user_id',auth()->user()->id)->first();
            @endphp
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
                {{$user->stat->name}}

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
                    <i class="fas @if($repair->value == 1) fa-check-square text-success @elseif($repair->value == 2) fa-window-close text-danger @else fa-square  @endif fa-lg"></i>
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
                <td><img alt="BTS" width="100" src="{{$path}}/{{$card->image}}"></td>
            </tr>
        @endif
    @endforeach
    @if($settings)
        <tr>
            <td colspan="3">
                <b>
                    Terms & conditions
                </b>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                {!! $settings->terms_conditions !!}
            </td>
        </tr>
    @endif
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

</body>
</html>
