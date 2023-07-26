@extends('admin.layouts.master')
@section('title',$title)
@section('content')
<style>
    .card {
        background-color: #fff;
        border-radius: 10px;
        border: none;
        position: relative;
        margin-bottom: 30px;
        box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
    }
    .l-bg-cherry {
        background: linear-gradient(to right, #493240, #f09) !important;
        color: #fff;
    }

    .l-bg-blue-dark {
        background: linear-gradient(to right, #373b44, #4286f4) !important;
        color: #fff;
    }

    .l-bg-green-dark {
        background: linear-gradient(to right, #0a504a, #38ef7d) !important;
        color: #fff;
    }

    .l-bg-orange-dark {
        background: linear-gradient(to right, #a86008, #ffba56) !important;
        color: #fff;
    }

    .card .card-statistic-3 .card-icon-large .fas, .card .card-statistic-3 .card-icon-large .far, .card .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
        font-size: 110px;
    }

    .card .card-statistic-3 .card-icon {
        text-align: center;
        line-height: 50px;
        margin-left: 15px;
        color: #000;
        position: absolute;
        right: -5px;
        top: 20px;
        opacity: 0.1;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }

    .l-bg-green {
        background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
        color: #fff;
    }

    .l-bg-orange {
        background: linear-gradient(to right, #f9900e, #ffba56) !important;
        color: #fff;
    }

    .l-bg-cyan {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: #fff;
    }
</style>

    <div class="container">
        <div class="row">
            <div class="col-md-6  mt-5">
                <div class="col-xl-12 col-lg-12">
                   <div class="col-md-12 ">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6">
                                <div class="card l-bg-cherry">
                                    <div class="card-statistic-3 p-4">
                                        <div class="card-icon card-icon-large"><i class="fas fa-shopping-cart"></i></div>
                                        <div class="mb-4">
                                            <h5 class="card-title mb-0">Fleet Tickets</h5>
                                        </div>
                                        <div class="row align-items-center mb-2 d-flex">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    {{$fleetticket ?? "0"}}
                                                </h2>
                                            </div>

                                        </div>
                                        <div class="progress mt-1 " data-height="8" style="height: 8px;">
                                            <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="card l-bg-blue-dark">
                                    <div class="card-statistic-3 p-4">
                                        <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                                        <div class="mb-4">
                                            <h5 class="card-title mb-0">Fleet Complaints</h5>
                                        </div>
                                        <div class="row align-items-center mb-2 d-flex">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    {{$complain ?? "0"}}
                                                </h2>
                                            </div>

                                        </div>
                                        <div class="progress mt-1 " data-height="8" style="height: 8px;">
                                            <div class="progress-bar l-bg-green" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--  <div class="row">
                            <div class="col-xl-6 col-lg-12">
                                <div class="card l-bg-green-dark">
                                    <div class="card-statistic-3 p-4">
                                        <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>
                                        <div class="mb-4">
                                            <h5 class="card-title mb-0">Total Vehicles</h5>
                                        </div>
                                        <div class="row align-items-center mb-2 d-flex">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    {{$total_vehicle ?? "0"}}
                                                </h2>
                                            </div>

                                        </div>
                                        <div class="progress mt-1 " data-height="8" style="height: 8px;">
                                            <div class="progress-bar l-bg-orange" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="card l-bg-orange-dark">
                                    <div class="card-statistic-3 p-4">
                                        <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                                        <div class="mb-4">
                                            <h5 class="card-title mb-0">Total Articles</h5>
                                        </div>
                                        <div class="row align-items-center mb-2 d-flex">
                                            <div class="col-8">
                                                <h2 class="d-flex align-items-center mb-0">
                                                    {{$articles ?? "0"}}
                                                </h2>
                                            </div>

                                        </div>
                                        <div class="progress mt-1 " data-height="8" style="height: 8px;">
                                            <div class="progress-bar l-bg-cyan" role="progressbar" data-width="25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  --}}
                    </div>
                </div>

            </div>
            <div class="col-md-6  mt-5 ">
                    <div class="card overflow-auto py-2" style="height:30vh !important">
                        <div class="card-body ">
                            <h5 class="card-title">Top Latest 10 Tickets</h5>

                            @if($vehicles->count() > 0)

                                    <table class="table table-striped py-2">
                                        <thead>
                                            <tr>
                                            <th scope="col">Vehicle Milage</th>
                                            <th scope="col">Vehicle No</th>
                                            <th scope="col">Complaint</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($vehicles as $vehicle)
                                                <tr>
                                                <th >{{$vehicle->vehicle_mileage ?? '' }}</th>
                                                <td>{{$vehicle->vehicles->vehicle_no ?? ''}}</td>
                                                <td>
                                                     {{  $vehicle->complaint ?? ''}}

                                                    </td>

                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                            @endif

                        </div>
                    </div>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-md-12">
                <div class="card overflow-auto py-2" style="height:30vh !important">
                    <div class="card-body ">
                        <h5 class="card-title">Top Latest 10 Departments</h5>

                        @if($links->count() > 0)


                                <table class="table table-striped py-2">
                                    <thead>
                                        <tr>
                                         <th scope="col">Name</th>
                                        <th scope="col">Title </th>
                                        <th scope="col">Department Link</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($links as $link)
                                        <tr>
                                        <th >{{$link->name }}</th>
                                        <td>{{$link->url}}</td>
                                        <td>{{$link->title}}</td>

                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>


                        @endif

                    </div>
                </div>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-md-12">
                <div class="card overflow-auto py-2" style="height:30vh !important">
                    <div class="card-body ">
                        <h5 class="card-title">Top Latest 10 Categories</h5>

                        @if($categories->count() > 0)


                                <table class="table table-striped py-2">
                                    <thead>
                                        <tr>
                                        <th scope="col">Name </th>
                                        <th >Create At </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                        <th >{{$category->name }}</th>
                                        <td>{{$category->created_at}}</td>

                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>


                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('stylesheets')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" cros
@endsection
@section('scripts')
<script>
    /* chart.js chart examples */

// chart colors
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];

/* large line chart */
var chLine = document.getElementById("chLine");
var chartData = {
  labels: ["S", "M", "T", "W", "T", "F", "S"],
  datasets: [{
    data: [589, 445, 483, 503, 689, 692, 634],
    backgroundColor: 'transparent',
    borderColor: colors[0],
    borderWidth: 4,
    pointBackgroundColor: colors[0]
  }
//   {
//     data: [639, 465, 493, 478, 589, 632, 674],
//     backgroundColor: colors[3],
//     borderColor: colors[1],
//     borderWidth: 4,
//     pointBackgroundColor: colors[1]
//   }
  ]
};
if (chLine) {
  new Chart(chLine, {
  type: 'line',
  data: chartData,
  options: {
    scales: {
      xAxes: [{
        ticks: {
          beginAtZero: false
        }
      }]
    },
    legend: {
      display: false
    },
    responsive: true
  }
  });
}

/* large pie/donut chart */
var chPie = document.getElementById("chPie");
if (chPie) {
  new Chart(chPie, {
    type: 'pie',
    data: {
      labels: ['Desktop', 'Phone', 'Tablet', 'Unknown'],
      datasets: [
        {
          backgroundColor: [colors[1],colors[0],colors[2],colors[5]],
          borderWidth: 0,
          data: [50, 40, 15, 5]
        }
      ]
    },
    plugins: [{
      beforeDraw: function(chart) {
        var width = chart.chart.width,
            height = chart.chart.height,
            ctx = chart.chart.ctx;
        ctx.restore();
        var fontSize = (height / 70).toFixed(2);
        ctx.font = fontSize + "em sans-serif";
        ctx.textBaseline = "middle";
        var text = chart.config.data.datasets[0].data[0] + "%",
            textX = Math.round((width - ctx.measureText(text).width) / 2),
            textY = height / 2;
        ctx.fillText(text, textX, textY);
        ctx.save();
      }
    }],
    options: {layout:{padding:0}, legend:{display:false}, cutoutPercentage: 80}
  });
}

/* bar chart */
var chBar = document.getElementById("chBar");
if (chBar) {
  new Chart(chBar, {
  type: 'bar',
  data: {
    labels: ["S", "M", "T", "W", "T", "F", "S"],
    datasets: [{
      data: [589, 445, 483, 503, 689, 692, 634],
      backgroundColor: colors[0]
    },
    {
      data: [639, 465, 493, 478, 589, 632, 674],
      backgroundColor: colors[1]
    }]
  },
  options: {
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        barPercentage: 0.4,
        categoryPercentage: 0.5
      }]
    }
  }
  });
}

/* 3 donut charts */
var donutOptions = {
  cutoutPercentage: 85,
  legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}
};

// donut 1
var chDonutData1 = {
    labels: ['Bootstrap', 'Popper', 'Other'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [74, 11, 40]
      }
    ]
};

var chDonut1 = document.getElementById("chDonut1");
if (chDonut1) {
  new Chart(chDonut1, {
      type: 'pie',
      data: chDonutData1,
      options: donutOptions
  });
}

// donut 2
var chDonutData2 = {
    labels: ['Wips', 'Pops', 'Dags'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [40, 45, 30]
      }
    ]
};
var chDonut2 = document.getElementById("chDonut2");
if (chDonut2) {
  new Chart(chDonut2, {
      type: 'pie',
      data: chDonutData2,
      options: donutOptions
  });
}

// donut 3
var chDonutData3 = {
    labels: ['Angular', 'React', 'Other'],
    datasets: [
      {
        backgroundColor: colors.slice(0,3),
        borderWidth: 0,
        data: [21, 45, 55, 33]
      }
    ]
};
var chDonut3 = document.getElementById("chDonut3");
if (chDonut3) {
  new Chart(chDonut3, {
      type: 'pie',
      data: chDonutData3,
      options: donutOptions
  });
}

/* 3 line charts */
var lineOptions = {
    legend:{display:false},
    tooltips:{interest:false,bodyFontSize:11,titleFontSize:11},
    scales:{
        xAxes:[
            {
                ticks:{
                    display:false
                },
                gridLines: {
                    display:false,
                    drawBorder:false
                }
            }
        ],
        yAxes:[{display:false}]
    },
    layout: {
        padding: {
            left: 6,
            right: 6,
            top: 4,
            bottom: 6
        }
    }
};

var chLine1 = document.getElementById("chLine1");
if (chLine1) {
  new Chart(chLine1, {
      type: 'line',
      data: {
          labels: ['Jan','Feb','Mar','Apr','May'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [10, 11, 4, 11, 4],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
var chLine2 = document.getElementById("chLine2");
if (chLine2) {
  new Chart(chLine2, {
      type: 'line',
      data: {
          labels: ['A','B','C','D','E'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [4, 5, 7, 13, 12],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}

var chLine3 = document.getElementById("chLine3");
if (chLine3) {
  new Chart(chLine3, {
      type: 'line',
      data: {
          labels: ['Pos','Neg','Nue','Other','Unknown'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [13, 15, 10, 9, 14],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
</script>
@endsection


