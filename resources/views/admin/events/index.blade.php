@extends('admin.layouts.master')
@section('title',$title)
@section('stylesheets')


@section('content')
<!--begin::Card-->
@include("admin.partials._messages")
<div class="card card-custom">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Calendar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-info btn-sm" href="{{ route('events.create') }}">
                            <i class="fas fa-pencil-alt">
                            </i>
                            Add Event
                        </a>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    {{--<div class="card-header">
            <div class="card-title">
                <h3 class="card-label">
                    Calendar
                </h3>
                <div class="d-flex align-items-center ">
                </div>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('events.create') }}" class="btn btn-light-primary font-weight-bold">
    <i class="ki ki-plus "></i> Add Event
    </a>
</div>
</div>--}}
<div class="card-body p-0">
    <div id="kt_calendar"></div>
</div>
<div class="modal fade" id="eventModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Event Detail</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                {{-- @if($events)--}}

                {{-- @endif--}}
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
<!--end::Card-->
@endsection
@section('stylesheets')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('scripts')
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

<script>
    var KTCalendarBasic = function() {

        return {
            //main function to initiate the module
            init: function() {
                var todayDate = moment().startOf('day');
                var YM = todayDate.format('YYYY-MM');
                var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
                var TODAY = todayDate.format('YYYY-MM-DD');
                var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

                var calendarEl = document.getElementById('kt_calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list'],
                    themeSystem: 'bootstrap',

                    isRTL: KTUtil.isRTL(),

                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },

                    height: 800,
                    contentHeight: 780,
                    aspectRatio: 3, // see: https://fullcalendar.io/docs/aspectRatio

                    nowIndicator: true,
                    now: TODAY + 'T09:25:00', // just for demo

                    views: {
                        dayGridMonth: {
                            buttonText: 'month'
                        },
                        timeGridWeek: {
                            buttonText: 'week'
                        },
                        timeGridDay: {
                            buttonText: 'day'
                        }
                    },

                    defaultView: 'dayGridMonth',
                    defaultDate: TODAY,

                    eventClick: function(event, jsEvent, view) {
                        $('#modalTitle').html(event.title);
                        $('#modalBody').html(event.description);
                        $('#eventUrl').attr('href', event.url);
                        $('#calendarModal').modal();
                    },

                    editable: true,
                    eventLimit: true, // allow "more" link when too many events
                    navLinks: true,
                    events: [
                        @foreach($events as $event) {
                            title: "{{ $event->title }}",
                            url: "#{{$event->id}}",
                            start: "{{ $event->start_time }}",
                            className: "fc-event-solid-info event-popup fc-event-light"
                        },
                        @endforeach
                    ],

                    eventRender: function(info) {
                        var element = $(info.el);

                        if (info.event.extendedProps && info.event.extendedProps.description) {
                            if (element.hasClass('fc-day-grid-event')) {
                                element.data('content', info.event.extendedProps.description);
                                element.data('placement', 'top');
                                KTApp.initPopover(element);
                            } else if (element.hasClass('fc-time-grid-event')) {
                                element.find('.fc-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                            } else if (element.find('.fc-list-item-title').lenght !== 0) {
                                element.find('.fc-list-item-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
                            }
                        }
                    }
                });

                calendar.render();
            }
        };
    }();

    function viewInfo(id) {
        // $.LoadingOverlay("show");
        var CSRF_TOKEN = '{{ csrf_token() }}';
        $.post("{{ route('admin.getEvent') }}", {
            _token: CSRF_TOKEN,
            id: id
        }).done(function(response) {
            // Add response in Modal body
            $('.modal-body').html(response);

            // Display Modal
            $('#eventModal').modal('show');
            // $.LoadingOverlay("hide");

        });
    }

    function printDiv() {
        document.getElementById("d-print").style.display = "none";
        document.getElementById("events").style.display = "none";
        document.getElementById("images").style.display = "none";

        var divContents = document.getElementById("GFG").innerHTML;

        var a = window.open('', '', 'height=500, width=500');
        a.document.write('<html>');

        a.document.write(divContents);

        a.document.write('<style> img {max-width : 300px; padding: 5px; white-space: nowrap;  height : auto; flex: 33.33%;} ');
      
       

        setTimeout(function() {
            a.document.close();
            a.print();
        }, 650);
    }
    jQuery(document).ready(function() {
        KTCalendarBasic.init();
    });

    $("body").on("click", ".event-popup", function() {
        var id = $(this).attr("href");
        id = id.replace('#', '');
        viewInfo(id);
    });
</script>
@endsection