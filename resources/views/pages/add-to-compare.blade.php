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
                        <form action="{{route('compare')}}" method="post">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table_list table-hover table-lg text-center">
                                    <thead>
                                        <th width="280">Sr. No</th>
                                        <th>Product Name </th>
                                        <th>Product Image </th>
                                    </thead>
                                    <tbody>
                                        @if($products!=null)
                                            @forelse($products as $product)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product['item']->name }}
                                                    <input type="hidden" name="product_id[]" id="product_id" value="{{ $product['item']->name }}">
                                                </td>
                                                <td>   @php $image = $product['item']->image; @endphp
                                                    <img src='{{ $image }}' width="50" height="50" ></td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3">You haven't added any product in compare list</td>
                                            </tr>
                                            @endforelse
                                        @else
                                            <tr>
                                                <td colspan="3">You haven't added any product in compare list</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @if($products!=null)
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
                            <div class="row" style="margin: 2% 0 ;">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary">Compare</button>
                                </div>
                            </div>
                            @endif
                        </form>
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