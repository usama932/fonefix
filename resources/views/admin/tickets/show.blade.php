<div class="card-datatable table-responsive" >
    <table id="tickets" class="datatables-demo table table-striped table-bordered">
        <tbody>

        <tr>
            <th>Vehicle #</th>

            <td>{{$ticket->vehicles->vehicle_no}}</td>


        </tr>
        <tr>
            <th>Vehicle Registration #</th>
            <td>{{$ticket->vehicles->Registration_no}}</td>
        </tr>
        <tr>
            <th>Vehicle Make</th>
            <td>{{$ticket->vehicles->make}}</td>
        </tr>
        <tr>
            <th>Vehicle Model</th>
            <td>{{$ticket->vehicles->model}}</td>
        </tr>
        <tr>
            <th>Vehicle Notes</th>
            <td>{!! $ticket->vehicles->notes !!}</td>
        </tr>
        @if($ticket->vehicles->image)
        <tr>
            <th>Vehicle Image</th>
            @php $veh_img = $ticket->vehicles->image; @endphp
            <td> <img src="{{asset('uploads/'.$veh_img)}}"  style="width:100%;" alt="Image is not found." /></td>
        </tr>
        @endif

        <tr>
            <th>Fleet Ticket Complaint</th>
            <td>{{$ticket->complaint}}</td>
        </tr>
        <tr>
            <th>Remark</th>
            <td>{!! $ticket->remarks !!}</td>
        </tr>
        <tr>
            <th>Image</th>
            <td id="printable">@php $c_images = \App\Models\TicketImage::where([["type",1],["ticket_id",$ticket->id]])->get();

                  @endphp
                @foreach($c_images as $image)
                    <div class="col-sm-6">
                        <img src="{{asset('uploads/'.$image->image)}}"  style="width:100%;" alt="Image is not found." />
                        <?php $serviceId = $ticket->id; $imageId = $image->id?>
                    </div>
                @endforeach
            </td>
        </tr>
        <tr>
            <th>Vehicle Mileage</th>
            <td>{{$ticket->vehicle_mileage }} </td>
        </tr>
        </tbody>
    </table>
</div>
<div style="display: none" id="tpt">
    <div class=""  style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                        <!-- START Top CONTENT AREA -->
                        <tr>
                            <td class="wrapper" style="border-bottom: 1px solid #d9d9d9; font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: middle; width: 50%;">
                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                                                <tbody>
                                                <tr>
                                                    <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                            <tbody>
                                                            <tr>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="font-family: sans-serif; font-size: 12px; vertical-align: middle;text-align: right; width: 50%;">
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">Anytown USA</p>
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">123 Main St, Anytown CT 01234</p>
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">203-555-1234</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END Top CONTENT AREA -->

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                            <p style="font-family: sans-serif; font-size: 24px; font-weight: normal; margin: 0; Margin-bottom: 5px;">{{$ticket->vehicles->vehicle_no}}</p>
                                            <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 5px;"> </p>
                                            <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 15px;">{{$ticket->created_at}}</p>
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">{{ $ticket->remarks }}</p>
                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box; margin-top: 25px;">
                                                <tbody>
                                                <tr>
                                                    <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                            <tbody>
                                                            <tr>
                                                                <td style="vertical-align: top;text-align: center; max-width: 100%;">
                                                                    <div class="form-group row" >
                                                                        @php $c_images = \App\Models\TicketImage::where([["type",1],["ticket_id",$ticket->id]])->get();  @endphp
                                                                        @foreach($c_images as $image)
                                                                            <img src="{{asset('uploads/'.$image->image)}}"  style="width:40%;" alt="Image is not found." />
                                                                            <?php $serviceId = $ticket->id; $imageId = $image->id?>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- END MAIN CONTENT AREA -->
                    </table>

                    <!-- START FOOTER -->
                    <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                            <tr>
                                <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->

                    <!-- END CENTERED WHITE CONTAINER -->
                </div>
            </td>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        </tr>
    </table>
    </div>
</div>

<input class="btn btn-light-primary font-weight-bold" type="button" value="Print" onclick="printDiv()">
