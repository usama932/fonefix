<!doctype html>
<html>

<head>
    <?php
    $setting = \App\Models\Setting::pluck('value', 'name')->toArray();
    $logo = isset($setting['logo']) ? 'uploads/' . $setting['logo'] : 'assets/media/logos/logo-light.png';
    $favicon = isset($setting['favicon']) ? 'uploads/' . $setting['favicon'] : 'assets/media/logos/favicon.ico';
    $copy_right = isset($setting['copy_right']) ? $setting['copy_right'] : 'wwww.webexert.com';
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
    <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden;  visibility: hidden; width: 0;">This is preheader text. Some clients will show this text as a preview.</span>
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate;  width: 100%; background-color: #f6f6f6;">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table class="main" style="border-collapse: separate; width: 100%; background: #ffffff; border-radius: 3px;">

                        <!-- START Top CONTENT AREA -->
                        <tr>
                            <td class="wrapper" style="border-bottom: 1px solid #d9d9d9; font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;  width: 100%;">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: middle; width: 50%;">
                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; width: 100%; box-sizing: border-box;">
                                                <tbody>
                                                    <tr>
                                                        <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate;  width: auto;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="vertical-align: top;text-align: center; max-width: 100%;">
                                                                            <img alt="Logo" width="100%" src="{{ asset($logo) }}" />
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
                                                Anytown USA</p>
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">
                                                123 Main St, Anytown CT 01234</p>
                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; Margin-bottom: 2px;">
                                                203-555-1234</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <!-- END Top CONTENT AREA -->

                        <!-- START MAIN CONTENT AREA -->


                        <!-- END MAIN CONTENT AREA -->
                    </table>

                    <!-- START FOOTER -->

                    <!-- END FOOTER -->

                    <!-- END CENTERED WHITE CONTAINER -->
                </div>
            </td>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        </tr>
    </table>

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
                                                Support Message!</h1>

                                        </td>
                                    </tr>
                                    <tr style="text-align:center;margin-top:15px;">
                                        <td><img src="{{ asset('uploads/'.$logo) }}" /></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left">
                                            <p style="font-size:18px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;">
                                                Hello {{$data['name']}},</p>
                                            <p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;">
                                                You have received a new Support Message .Below are the details:</p>
                                            <p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;">
                                                <span>Name : <strong>{{$data['name']}}</strong></span>
                                                <br>
                                                <span>Subject : <strong>{{$data['subject']}}</strong></span>
                                            </p>
                                            <p>
                                                <span>Message : <strong> {{$data['msg']}}</strong></span>
                                            </p>

                                        </td>
                                    </tr>


                                </table>
                                <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                                    <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                                        <tr>
                                            <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                                                <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">Copyright © {{ date('Y') }} {{ $copy_right }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <p style="text-align:center; margin:35px auto 0px; font-size:14px; color:#5d5d5d; width:500px; line-height:25px;">
                        © {{ date('Y') }} PIN </p>
                </td>
            </tr>

        </tbody>
    </table>
</body>

</html>