<style>
    @media print {
        .noprint {
            visibility: hidden;
        }
    }
</style>


<div class=" card-datatable table-responsive" id="GFG">






        <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

            <!-- <tr>
                <td class="wrapper" style="border-bottom: 1px solid #d9d9d9; font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                    <!-- <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                        <tr>
                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: middle; width: 50%;">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                                    <tbody>
                                        <tr>
                                            <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="vertical-align: top;text-align: center; max-width: 100%;"></td>
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
                    </table> -->
                <!-- </td>
            </tr> --> 
            <tr>
                <table id="users" class="datatables-demo table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{$event->title}}</td>
                        </tr>
                        <tr>
                            <td>Narrative</td>
                            <td>{!! $event->narrative !!}</td>
                        </tr>
                        {{-- <tr>--}}
                        {{-- <td>User Name</td>--}}
                        {{-- <td>{{($event->user) ? $event->user->name : "" }}
                        </td>--}}
                        {{-- </tr>--}}
                        <tr>
                            <td>Created By</td>
                            <td>{{($event->created_one) ? $event->created_one->name : "" }}</td>
                        </tr>
                        @if(isset($event->event_category->name))
                        <tr>
                            <td>Category</td>
                            <!-- <td>{{($event->event_category) ? $event->event_category->name : "" }}</td> -->

                            <td>{{ $event->event_category->name}}</td>


                        </tr>
                        @endif
                        <tr>
                            <td>Start Date & Time</td>
                            <td>{{$event->start_time}}</td>
                        </tr>
                        <tr>
                            <td>End Date & Time</td>
                            <td>{{$event->end_time}}</td>
                        </tr>
                        <tr>
                            <td>Created at</td>
                            <td>{{$event->created_at}}</td>
                        </tr>
                        @if($event->event_file)
                        <tr>
                            <td>File</td>
                            <td>
                                @if(strpos($event->event_file,'.png') !== false or strpos($event->event_file,'.jpg') !== false or strpos($event->event_file,'.jpeg') !== false)
                                <img src="{{asset('uploads/'.$event->event_file)}}" style="width:100%;" alt="Image is not found." />
                                @else
                                <a href="{{asset('uploads/'.$event->event_file)}}" target="_blank">{{$event->event_file}}</a>
                                @endif
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td id="events">
                                <div class="d-print-none">
                                    @if(Auth::user()->role == 1 or Auth::user()->role ==3)
                                    <a href="events/{{$event->id}}/edit" class=" btn btn-light-primary font-weight-bold">
                                        <i class="con-1x  flaticon-edit"></i> Edit Event
                                    </a>
                                    @elseif(Auth::user()->role == 2 and Auth::user()->id == $event->user_id)
                                    <a href="events/{{$event->id}}/edit" class="  btn btn-light-primary font-weight-bold">
                                        <i class="con-1x  flaticon-edit"></i> Edit Event
                                    </a>
                                    @endif
                                    @if(Auth::user()->role == 1 or Auth::user()->role ==3)
                                    <a href="{{route("event.delete",$event->id)}}" class=" btn btn-light-danger font-weight-bold">
                                        <i class="con-1x  flaticon-trash"></i> Delete Event
                                    </a>
                                    @elseif(Auth::user()->role == 2 and Auth::user()->id == $event->user_id)
                                    <a href="{{route("event.delete",$event->id)}}" class="btn btn-light-danger font-weight-bold">
                                        <i class="con-1x  flaticon-trash"></i> Delete Event
                                    </a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($event->event_file)

                                <a href="events/{{$event->uuid}}/download" id="images" class="d-print-none btn btn-light-primary font-weight-bold">
                                    <i class="con-1x  flaticon-download"></i> Download File
                                </a>
                                @else
                                <p id="images" style="color:red;">

                                    <i class="con-1x  flaticon-download"></i> No Image Found
                                </p>
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </tr>
            <input class=" btn btn-light-primary font-weight-bold" id="d-print" value="Print" type="button" onclick="printDiv();">
        </table>
        <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                            <tr>
                                <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                                    <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Copyright © {{ date('Y') }} PIN, LLC All rights reserved</span>
                                </td>
                            </tr>
                        </table>
                    </div>
    


</div>