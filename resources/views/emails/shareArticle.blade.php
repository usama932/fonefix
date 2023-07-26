<!doctype html>
<html>

<head>
    <?php
    $setting = \App\Models\Setting::pluck('value', 'name')->toArray();
    $logo = isset($setting['print_logo']) ? 'uploads/' . $setting['print_logo'] : 'assets/media/logos/printlogo.png';
    $favicon = isset($setting['favicon']) ? 'uploads/' . $setting['favicon'] : 'assets/media/logos/favicon.ico';
    $copy_right = isset($setting['copy_right']) ? $setting['copy_right'] : 'wwww.webexert.com';
    $agency_name = isset($setting['agency_name']) ? $setting['agency_name'] : 'Anytown';
    $address = isset($setting['address']) ? $setting['address'] : '123 Main St, Anytown CT 01234';
    $contact = isset($setting['contact']) ? $setting['contact'] : ' 203-555-1234';
    ?>
    <title>ShareArticle Template</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<![endif]-->

    <style>
        @import 'https://fonts.googleapis.com/css?family=Raleway:300,400,500,700';

        body {
            font-family: 'Raleway', sans-serif;
        }
    </style>
</head>

<body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">

    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table class="main navbar navbar-dark bg-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                        <!-- START Top CONTENT AREA -->
                        <tr>
                            <td class="wrapper" style="border-bottom: 1px solid #d9d9d9; font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: middle; width: 50%;">
                                            <table border="0" class="navbar navbar-dark bg-primary" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                                                <tbody>
                                                    <tr align="left">
                                                        <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="navbar navbar-dark bg-primary" style="vertical-align: top;text-align: center; max-width: 100%;">
                                                                            <img alt="" src="{{ asset($logo) }}" style="max-width: 100%;" />
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="font-family: sans-serif; font-size: 12px; vertical-align: middle;text-align: right; width: 50%;">
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">
                                                {{ $agency_name }}
                                            </p>
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">
                                                {{ $address }}
                                            </p>
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">
                                                {{ $contact }}
                                            </p>
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
                                        <td   style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                            <p  style="font-family: sans-serif; font-size: 24px; font-weight: normal; margin: 0; Margin-bottom: 5px;">{{$data['name']}}</p>
                                            <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 5px;"><b>{{ $data['title'].' - '. $data['category'] }}</b></p>
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><b>Author:{!! $data['author'] !!}</b></p>
                                            <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><b>{{$data['created_at']}}</b></p>
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">{!! $data['content'] !!}</p>

                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box; margin-top: 25px;">
                                                <tbody>
                                                    <tr>
                                                        <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="vertical-align: top;text-align: center; max-width: 100%;">
                                                                            <div class="form-group row">
                                                                                @php 
                                                                                $c_images = \App\Models\ArticleImage::where('article_id',$data['article_id'])->get(); 
                                                                                @endphp
                                                                                @foreach($c_images as $image)
                                                                                <img
                                                                                        src="{{asset('uploads/'.$image->image)}}"
                                                                                        style="width:40%;"
                                                                                        alt="Image is not found."/>
                                                                            
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
                                    <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Copyright ©  {{ $copy_right }}</span>
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
</body>

</html>