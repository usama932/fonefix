@extends('layouts.master')
@section('content')
<main class="d-flex flex-column justify-content-start flex-grow-1 ">
        <!-- page header Start-->
        <div class="page_header section_space">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="page_header_title title_divider text-uppercase mb-0">Compare List Detail</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- page header End-->

        <!-- Start-->
        <div class="section_space p-0">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                            <div class="table-responsive">
                                <table class="table table_list table-hover table-lg text-center">
                                    <thead>
                                        <tr>
                                            <th width="380">Shopping List</th>
                                            @for($i=0;$i<count($main[0]);$i++)
                                                <th scope="col">@php print_r($main[0][$i]['shop_name']);   @endphp  
                                                    <img class="ms-3" src='<?php echo asset("uploads/".$main[0][$i]['image']) ?>' width="20" data-toggle="tooltip" data-placement="top" title="<?php echo $main[0][$i]['address'];?>"></th>
                                            @endfor
                                            <th>Combined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $pric_column =0; @endphp
                                        @for($j=0;$j<count($main);$j++)
                                            <tr>
                                                <th scope="row">{{ $main[$j][0]['product_name']}}</th>
                                                @for($k=0;$k<count($main[$j]);$k++)
                                                    <th scope="row">${{ $main[$j][$k]['price']}}
                                                        @php $pric_column = $pric_column + $main[$j][$k]['price']; @endphp
                                                    </th>
                                                @endfor
                                                <th> ${{ $pric_column}}  @php $pric_column =0; @endphp</th>
                                            </tr>
                                        @endfor
                                        <tr>
                                            <th scope="col">Total</th>
                                            @php $total_price_sum =0; @endphp
                                             @for($l=0; $l < count($total_shop_price) ; $l++)
                                                <th scope="row"> ${{ $total_shop_price[$l]['shop_total_price']}}
                                                        @php $total_price_sum = $total_price_sum + $total_shop_price[$l]['shop_total_price']; @endphp
                                                </th>
                                             @endfor
                                             <th> ${{ $total_price_sum}}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End-->

       <!-- Newsletter Start-->
       <!-- @include('partials.newsletter')  -->
       <!-- Newsletter End-->


    </main>


@endsection
@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
      $('.select2').select2({
        placeholder: "Please Select",
      });

      function GetShops(id){
                    $.ajax({
                        type: "get",
                        url: '/get-shops/'+id,
                        success: function (data) {
                             document.getElementById('shop_id').innerHTML=data.shops;
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
    
                }
</script>

@endsection