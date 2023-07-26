@extends('layouts.master')
@section('content')

<main class="d-flex flex-column justify-content-start flex-grow-1 ">
        <!-- page header Start-->
        <div class="page_header section_space">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="page_header_title title_divider text-uppercase mb-0">Comparison List Detail</h2>
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
                                <table class="table table-bordered table-hover table-lg ">
                                    <thead class="table-dark table-head-red">
                                        <tr valign="middle">
                                            <th width="380">Shopping List</th>
                                            <!-- Showing table header(store name) -->
                                            @for($i=0;$i<count($store_header);$i++)
                                                <th scope="col" class="text-center">@php print_r($store_header[$i]['shop_name']);   @endphp
                                                    <img class="ms-2" src='<?php echo asset("uploads/".$store_header[$i]['image']) ?>' width="25" data-toggle="tooltip" data-placement="top" title="<?php echo $store_header[$i]['address'];?>" style="width: 25px;height: 25px;object-fit: cover;"></th>
                                            @endfor
                                            <th class="text-center">Combined Cheapest</th>
                                            <th>Absolute Cheapest</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $price_column =0; $price_column_total=0; $total_absolute_cheapest=0;  $not_found =0;@endphp
                                        @for($j=0;$j<count($fianl);$j++)
                                            <tr valign="middle">
                                            <!-- Showing product name in left bar -->
                                                <th scope="row" >
                                                    @php $product_name = '';
                                                         $p_name ='';
                                                         $p_weight ='';
                                                    
                                                    @endphp
                                                    @for($pro_name=0; $pro_name<count($fianl[$j]); $pro_name++)
                                                        @if(isset($fianl[$j][$pro_name]['name']))
                                                            @php $product_name = $fianl[$j][$pro_name]['name'] .' ' .$fianl[$j][$pro_name]['size']; @endphp
                                                        @else
                                                            @php
                                                                $product_name = $product_not_found[$not_found]['name'];  
                                                                $p_name =$product_not_found[$not_found]['product_name'];
                                                                $p_weight =$product_not_found[$not_found]['weight'];
                                                                $not_found=$not_found+1;
                                                            @endphp
                                                        @endif
                                                    @endfor
                                                  
                                                    {{ $product_name }}
                                                </th>
                                                        <!-- Showing product price according to store and its products  -->
                                                @for($k=0;$k<count($fianl[$j]);$k++)
                                                    @if(count($fianl[$j][$k])>0)
                                                    <th scope="row" class="text-center" @if($fianl[$j][$k]['low']==1) style="background-color: #59AF49;color:white" @endif>${{ $fianl[$j][$k]['price']}}

                                                        @php

                                                            if($price_column ==0){
                                                                $price_column =$fianl[$j][$k]['price'];
                                                            }
                                                            if($fianl[$j][$k]['price'] < $price_column){
                                                                $price_column =$fianl[$j][$k]['price'];
                                                            }

                                                        @endphp
                                                    </th>
                                                    @else
                                                    <th scope="row" class="text-center">N/A
                                                        @php $price_column = $price_column + 0; @endphp
                                                    </th>
                                                    @endif
                                                @endfor
                                                <th class="text-center"> ${{ $price_column}}  @php $price_column_total = $price_column_total +$price_column; $price_column =0; @endphp</th>
                                                <th class="text-center">
													<table class="w-100">
														<tbody>
														    <!-- getting abolute cheapest product with in given zipcode. over all  -->
															<tr>
															 <?php $shop_id=0; $shop_name =''; $shop_image=''; $shop_address='';$size=''; $cheap_price='N/A';
                                                                    for($pro_name=0; $pro_name<count($fianl[$j]); $pro_name++){
                                                                        if(isset($fianl[$j][$pro_name]['name'])){
                                                                            $shop_name =$fianl[$j][$pro_name]['name'];
                                                                            $shop_id =$fianl[$j][$pro_name]['shop_id'];
                                                                            $size=$fianl[$j][$pro_name]['size'];
                                                                        }
                                                                    }


                                                            ?>
															@php
                                                                if($shop_id!=0){
                                                                    $cheap = \App\Http\Controllers\ProductController::getAbsoluteCheapest($shop_id,$shop_name,$size);
                                                                    $total_absolute_cheapest = $total_absolute_cheapest + $cheap['price'];
                                                                    $cheap_price = $cheap['price'];
                                                                    $shop_address=$cheap['shop']->address;
                                                                    $shop_image=$cheap['shop']->image;
															    }else{
                                                                    $cheap = \App\Http\Controllers\ProductController::getAbsoluteCheapestByProduct($p_name,$p_weight);
                                                                    $total_absolute_cheapest = $total_absolute_cheapest + $cheap['price'];
                                                                    $cheap_price = $cheap['price'];
                                                                    $shop_address=$cheap['shop']->address;
                                                                    $shop_image=$cheap['shop']->image;
                                                                    
                                                                }


															@endphp
																<td style="width: 50%;">
																	@if($cheap_price!='N/A') $ @endif {{ $cheap_price }}
																</td>
																<td style="width: 50%;">
																	<img src='<?php echo asset("uploads/".$shop_image) ?>' width="25" data-toggle="tooltip" data-placement="top" title="<?php echo $shop_address;?>" style="width: 25px;height: 25px;object-fit: cover;">
																</td>
															</tr>
														</tbody>
													</table>
                                                </th>
                                            </tr>
                                        @endfor
                                            <tr valign="middle">
                                                <th scope="row">Total</th>
                                                @php $total_price_sum =0; @endphp
                                        @for($l=0;$l<count($st_total);$l++)
                                                <th scope="row" class="text-center">${{ $st_total[$l]}}</th>
                                                @php $total_price_sum = $total_price_sum + $st_total[$l]; @endphp
                                        @endfor
                                        <th class="text-center">${{ $price_column_total }}</th>
                                        <th>
											<table class="w-100 text-center">
												<tbody>
													<tr>
														<td style="width: 50%;">${{ $total_absolute_cheapest }}</td>
														<td style="width: 50%;"></td>
													</tr>
												</tbody>
											</table>
										</th>
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
