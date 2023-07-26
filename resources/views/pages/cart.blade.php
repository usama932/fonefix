@extends('layouts.master')

@section('content')

<main class="d-flex flex-column justify-content-start flex-grow-1 ">

<div>

    <div class="container">

    <!-- page header Start-->

    <div class="page_header section_space">

        <div class="container">

            <div class="row">

                <div class="col-md-12 text-center">

                    <h2 class="page_header_title title_divider text-uppercase mb-0">Compare List Detail</h2>

                </div>
				<div class="col-md-12 text-left">

					<a type="button" href="{{ url('clear-cart')}}"  class="btn btn-primary">Clear Cart</a>

				</div>
            </div>

        </div>

    </div>
	@if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
    <form action="{{route('compare')}}" method="post" id="compare_form">

        @csrf

        <!-- page header End-->

        <div class="table-scroll mb-5">

			<table id="cart" class="table table-hover table-condensed mb-0">

				<thead class="table-dark table-head-red">

					<tr>

						<th style="width:60%" colspan="2">Product</th>
						<th style="width:15%">Weight</th>
						<th style="width:15%">Price</th>

						<!-- <th style="width:8%">Quantity</th>

						<th style="width:22%" class="text-center">Subtotal</th> -->

						<th style="width:15%"></th>

					</tr>

				</thead>

				<tbody>

					@php $total = 0 @endphp

					@if(session('cart'))

					@foreach(session('cart') as $id => $details)

					@php $total += $details['price'] * $details['quantity'] @endphp

					<tr data-id="{{ $id }}">

						<td class="p-0 p-md-2" valign="middle">

							<img class="d-none d-md-block" src="{{ $details['image'] }}" width="75" height="75" class="img-responsive"/>

						</td>

						<td data-th="Product" valign="middle">

							<h6 class="mb-0">{{ $details['name'] }}</h6>

							<input type="hidden" name="product_id[]" id="product_id" value="{{ $details['name'].'--'.$details['size'] }}">

						</td>
						<td data-th="Weight" valign="middle">{{ $details['size'] }}</td>
						<td data-th="Price" valign="middle">${{ $details['price'] }}</td>

						a<!-- <td data-th="Quantity">

							<input type="number" value="{{ $details['quantity'] }}" class="form-control quantity update-cart" />

						</td>

						<td data-th="Subtotal" class="text-center">${{ $details['price'] * $details['quantity'] }}</td> -->

						<td class="actions" data-th="" valign="middle">

							<button class="btn btn-danger btn_sm remove-from-cart text-nowrap" style="color:white">

							    <i class="fas fa-times"></i>

							</button>

						</td>

					</tr>

					@endforeach

					<!-- <tr>

						<td colspan="4">

							<div class="row mb-20">

								<div class="col-6">

									<div class="form-group">

										<label for="zipcode"><strong> Zip Code</strong></label>

										<select name="zip_code" id="zip_code" class="select2 form-control" onchange="return GetShops(this.value)" required>

											<option value="">Pleaset Select Zipcode</option>

											@foreach($zipcodes as $code)

											<option value="{{ $code->id}}">{{ $code->zip_code}}</option>

											@endforeach

										</select>

									</div>

								</div>

								<div class="col-6">

									<div class="form-group">

										<label for="zipcode"><strong>Stores</strong></label>

										<select name="shop_id[]" id="shop_id" class="select2 form-control" multiple required>

										</select>

									</div>

								</div>

							</div>

						</td>

					</tr>-->

				</tbody>

				<tfoot>

					<!-- <tr>

						<td colspan="5" class="text-right"><h3><strong>Total ${{ $total }}</strong></h3></td>

					</tr> -->

					<tr>

						<td colspan="4">

						    <div class="col-12">

    						    <div class="row mb-20 g-3">

    								<div class="col-6">

    									<div class="form-group">

    										<label for="zipcode"><strong> Zip Code</strong></label>

    										<!-- <select name="zip_code" id="zip_code" class="select2 form-control" onchange="return GetShops(this.value)" required>

    											<option value="">Pleaset Select Zipcode</option>

    											@foreach($zipcodes as $code)

    											<option value="{{ $code->id}}">{{ $code->zip_code}}</option>

    											@endforeach

    										</select> -->
											<input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ \Session::get('post_code') }}" readonly>

    									</div>

    								</div>

    								<div class="col-6">

    									<div class="form-group">

    										<label for="zipcode"><strong>Stores</strong></label>
											{{session()->get('store_id')}}
    										<select name="shop_id[]" id="shop_id" class="select2 form-control" multiple required>
											@foreach($area_stores as $store)

												<option value="{{ $store->id}}" @if(session()->get('store_id')==$store->id) selected="selected" @endif>{{ $store->name}} ( {{ $store->address}} )</option>

											@endforeach

    										</select>

    									</div>

    								</div>

    							</div>

						    </div>

						</td>

					</tr>

					<tr>

						<td colspan="4" class="text-right">

						    <div class="d-flex align-items-center justify-content-md-between justify-content-center flex-column flex-md-row">

    							<a href="{{ url('/') }}" class="btn btn-warning mb-md-0 mb-2"><i class="fa fa-angle-left"></i> Continue Shopping</a>

    							<button type="button" class="btn btn-primary" onclick="return openComparisonAd()">Compare</button>

							</div>

						</td>

					</tr>

				</tfoot>

				@else

				<tbody>

					<tr>

						<td colspan="6">You haven't added any product yet </td>

					</tr>

				</tbody>

				@endif

			</table>

		</div>

    </form>

    </div>

</div>

@endsection



@section('scripts')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">

    var last_valid_selection = null;

  $("#shop_id").change(function(event) {

    if ($(this).val().length > 3) {

        $(this).val(last_valid_selection);

        alert('You can select upto 3 options only');

    } else {

        last_valid_selection = $(this).val();

    }

});

  $('.select2').select2({

        placeholder: "Please Select",
		tags: true

      });

	  $(".select2").select2().val({{session()->get('store_id')}}).trigger("change")

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

    $(".update-cart").change(function (e) {

        e.preventDefault();



        var ele = $(this);



        $.ajax({

            url: '{{ route('update.cart') }}',

            method: "patch",

            data: {

                _token: '{{ csrf_token() }}',

                id: ele.parents("tr").attr("data-id"),

                quantity: ele.parents("tr").find(".quantity").val()

            },

            success: function (response) {

               window.location.reload();

            }

        });

    });



    $(".remove-from-cart").click(function (e) {

        e.preventDefault();



        var ele = $(this);



        if(confirm("Are you sure want to remove?")) {

            $.ajax({

                url: '{{ route('remove.from.cart') }}',

                method: "DELETE",

                data: {

                    _token: '{{ csrf_token() }}',

                    id: ele.parents("tr").attr("data-id")

                },

                success: function (response) {

                    window.location.reload();

                }

            });

        }

    });



</script>

       <!-- Newsletter Start-->

       <!-- @include('partials.newsletter')  -->

       <!-- Newsletter End-->





    </main>





@endsection

@section('scripts')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>



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
