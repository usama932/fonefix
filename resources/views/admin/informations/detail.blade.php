    <div class="card-datatable table-responsive">
        <table id="clients" class="datatables-demo table table-striped table-bordered">
            <tbody>
            <tr>
                <td>Title</td>
                <td>{{$information->title}} </td>
            </tr>

            <tr>
                <td>Shops</td>

                <td>
                    @foreach ($information->users as $user)
                     {{ $loop->iteration }}.#      {{ $user->name }}.<br>
                    @endforeach
                </td>
            </tr>

            <tr>
                <td>Image.# 1</td>

                <td>
                    <div class="text-center ">
                    <img src="  {{asset('uploads')}}/{{$information->image_1}}" class="rounded" alt="..."  style="height: 150px !important; width:150px !important;">
                    </div>

                </td>
            </tr>

            <tr>
                <td>Image.# 2</td>

                <td>
                    <div class="text-center">
                        <img src="  {{asset('uploads')}}/{{$information->image_2}}" class="rounded" alt="..."  style="height: 150px !important; width:150px !important;">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Image.# 3</td>

                <td>
                    <div class="text-center">
                        <img src="  {{asset('uploads')}}/{{$information->image_3}}" class="rounded" alt="..." style="height: 150px !important; width:150px !important;">
                    </div>
                </td>
            </tr>

            <tr>
                <td>Image.# 4</td>

                <td>
                    <div class="text-center">
                        <img src="  {{asset('uploads')}}/{{$information->image_4}}" class="rounded"  style="height: 150px !important; width:150px !important;">
                    </div>
                </td>
            </tr>
            <tr>
                <td>Created at</td>

                <td>{{$information->created_at}} </td>
            </tr>
            </tbody>
        </table>

    </div>

