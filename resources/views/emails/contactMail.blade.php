<!doctype html>
<html lang="en">
<head>
<!--    --><?php
//    $setting = \App\Models\Setting::pluck('value', 'name')->toArray();
//    $logo = isset($setting['logo']) ? $setting['logo'] : 'assets/media/logos/logo-light.png';
//    $favicon = isset($setting['favicon']) ? 'uploads/' . $setting['favicon'] : 'assets/media/logos/favicon.ico';
//    $copy_right = isset($setting['copy_right']) ? $setting['copy_right'] : 'wwww.exposeyouragent.com.au';
//    ?>
    <title>Contact Template</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!--[if !mso]><!-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--<![endif]-->

    <style>
        @import 'https://fonts.googleapis.com/css?family=Raleway:300,400,500,700';

        body {
            font-family: 'Raleway', sans-serif;
        }
    </style>
</head>

<body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">



<table width="100%" cellspacing="50" cellpadding="50" border="0" bgcolor="#E7E7E7" class="wrapper">
    <tbody>
    <tr>
        <td>
            <table bgcolor="#ffffff" cellpadding="0" cellspacing="0" align="center" style="border:1px solid #acacac; border-radius:4px; padding:20px 50px 100px; width:632px;">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="text-align:left">
                                    <h1 style="font-weight:normal; color:#2e2e2e; font-size:30px; margin:0px; padding-top:60px;">
                                        Thank You For Contacting Us!!</h1>

                                </td>
                            </tr>
                            {{-- <tr style="text-align:center;margin-top:15px;">
                                <td><img src="{{ asset('uploads/'.$logo) }}" /></td>
                            </tr> --}}
                            <tr>
                                <td style="text-align:left">
                                    <p style="font-size:18px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;">
                                        Hello {{$contact['name']}},</p>
                                    <p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;">
                                        You have received a new support message .Below are the details:</p>
                                    <p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;">

                                        <br>
                                        <span>Last Name : <strong>{{$contact['name']}}</strong></span>
                                        <br>
                                        <span>Email : <strong>{{$contact['email']}}</strong></span>
                                        <br>
                                    </p>

                                    <p>
                                        <span>Message</span><br>
                                        {{$contact['comment']}}

                                    </p>

                                </td>
                            </tr>


                        </table>
                        <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                <tr>
                                    <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                                        <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Copyright © {{ date('Y') }} </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <p style="text-align:center; margin:35px auto 0px; font-size:14px; color:#5d5d5d; width:500px; line-height:25px;">
                © {{ date('Y') }} {{ config('app.name')}} </p>
        </td>
    </tr>

    </tbody>
</table>
</body>

</html>
