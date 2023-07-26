<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        {{$user->number}}
    </title>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
        body {
            margin: 0 auto;
            max-width: 900px;
            font-family: sans-serif;
        }
        table, th, td {
            padding: 7px 10px;
        }
    </style>
</head>
<body>

<main>
    <!-- table Head -->
    <table style="border-collapse: collapse;border-spacing: 0;margin-top: 20px;margin-bottom: 10px;margin-right: 10px;display: inline-table;width: 100%;">
        <tbody>
        <tr>
            <td align="center" style="text-align:center;padding: 0;font-size: 40px;">
                @if($user->job_id)
                    {{$user->job->shop->name}}
                @else
                    {{$user->shop->name}}
                @endif
            </td>
        </tr>
        <tr>
            <td align="center" style="text-align:center;padding: 10px;font-size: 16px;">
                @if($user->job_id)
                    {{$user->job->shop->line1}}
                @else
                    {{$user->shop->line1}}
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <!-- table 1 -->
    <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
        <tbody>
        <tr>
            <td colspan="2" align="center" style="text-align:center;padding: 0;font-size: 32px;">
                Invoice
            </td>
        </tr>
        <tr>
            <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;">
                <strong>Invoice No.</strong> {{$user->number}}
            </td>
            <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;">
                <strong>Date</strong> {{date('Y-m-d',strtotime($user->created_at))}}
            </td>
        </tr>
        <tr>
            <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;">
                <strong>Customer</strong>: @if($user->job_id)
                    {{$user->job->customer->name}}
                @else
                    {{$user->customer->name}}
                @endif
            </td>
        </tr>
        <tr>
            <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;">
                <strong>Mobile</strong>: @if($user->job_id)
                    {{$user->job->customer->phone}}
                @else
                    {{$user->customer->phone}}
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <!-- table 2 -->
    <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
        <thead>
        <tr>
            <th align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 40%;">
                Product
            </th>
            <th align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 20%;">
                Quantity
            </th>
{{--            <th align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 20%;">--}}
{{--                Unit Price--}}
{{--            </th>--}}
            <th align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 20%;">
                Subtotal
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($user->parts as $part)
            <tr>
                <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">
                    {{$part->description}}
                </td>
                <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">
                    {{$part->quantity}} Pc(s)
                </td>
{{--                <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">--}}
{{--                    {{$part->amount}}--}}
{{--                </td>--}}
                <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;">
                    {{$part->amount * $part->quantity}}
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <!-- table 3 -->
    <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
        <tbody>
        <tr>
            <td valign="top" align="left" style="text-align: left;padding: 0;font-size: 16px;border-top: 1px solid #000000;width: 50%;">
                <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
                    <tbody>
                    <tr>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 33.33%;">
                            @if($user->payment_method == 1)
                                Card
                            @elseif($user->payment_method == 2)
                                Cash
                            @else
                                Credit
                            @endif
                        </td>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 33.33%;">
                            @if($user->discount_type == 1)
                                ₴ {{$user->total - $user->discount}}
                                @php $res = $user->discount;
                                @endphp
                            @else
                                @php $res = ( $user->total * $user->discount / 100 );

                                @endphp
                                ₴ {{$user->total - $res}}
                            @endif
                        </td>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 33.33%;">
                            {{$user->created_at}}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 33.33%;">
                            <strong>Total Paid</strong>
                        </td>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 33.33%;">
                            @if($user->discount_type == 1)
                                ₴ {{$user->total - $user->discount}}
                                @php $res = $user->discount;
                                @endphp
                            @else
                                @php $res = ( $user->total * $user->discount / 100 );

                                @endphp
                                ₴ {{$user->total - $res}}
                            @endif
                        </td>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 33.33%;">

                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td valign="top" align="left" style="text-align: left;padding: 0;font-size: 16px;border-top: 1px solid #000000;width: 50%;">
                <table style="border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;margin-right: 10px;display: inline-table;width: 100%;">
                    <tbody>
                    <tr>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;width: 50%;">
                            <strong>Subtotal:</strong>
                        </td>
                        <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;width: 50%;">
                            ₴ {{$user->total}}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                            <strong>Discount @if($user->discount_type == 2)(₴ {{$user->discount}}%) @endif:</strong>
                        </td>
                        <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                            (-) ₴ {{$res}}
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="text-align: left;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                            <strong>Total:</strong>
                        </td>
                        <td align="right" style="text-align: right;padding: 5px 0;font-size: 16px;border-top: 1px solid #d1d1d1;width: 50%;">
                            @if($user->discount_type == 1)
                                ₴ {{$user->total - $user->discount}}
                                @php $res = $user->discount;
                                @endphp
                            @else
                                @php $res = ( $user->total * $user->discount / 100 );

                                @endphp
                                ₴ {{$user->total - $res}}
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

</main>
</body>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
    $(document).ready(function() {
        window.print();
        return false;
    })
</script>
</html>
