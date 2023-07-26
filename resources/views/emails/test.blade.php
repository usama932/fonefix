<!doctype html>
<html>
<head>
	<title>Email Template</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!--[if !mso]><!-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<!--<![endif]-->

</head>

<body>

<table width="100%" cellspacing="50" cellpadding="50" border="0" bgcolor="#E7E7E7" class="wrapper">
	<tbody>
	<tr>
		<td>
			<table bgcolor="#ffffff" cellpadding="0" cellspacing="0" align="center" style="border:1px solid #acacac; border-radius:4px; padding:20px 50px 100px; width:632px;" >
				<tr>
					<td>
						<table style="width:100%">
							<tr style="text-align:center">
								<td><img src="https://www.parkhere.ca/uploads/1065857354ParkHere%20Logo%20(2).png" width="500" class="flexibleImage" style="max-width:252px;width:100%;display:block;margin-left: 121px;" alt="logo" title="logo"  /></td>
							</tr>
							
							<tr>
								<td style="text-align:left">
									<p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;" >
										Dear <strong>{{ $firstname }}</strong></p>
								</td>
							</tr>
							
							<tr>
								<td style="text-align:left">
									<p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;" >
										Thank you for joining the first ever test email. You will be required to generate a secure verification code every time you log in, so please allow 10 minutes to join prior to the start each session.</p>
								</td>
							</tr>
							
							<tr>
								<td style="text-align:left">
									<p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;" >
										Your code is: <strong>{{ $verification_code }}</strong></p>
								</td>
							</tr>
							
							
							<tr>
								<td style="text-align:left">
									<p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:20px;" >
										Thank you. </p>
								</td>
							</tr>
							
							<tr>
								<td style="text-align:left">
									<p style="font-size:16px; color:#2e2e2e; line-height:25px; margin:0px; padding-top:40px;" >
										This email is not monitored. Please do not reply. </p>
								</td>
							</tr>
						
						</table>
					</td>
				</tr>
			</table>
			<p style="text-align:center; margin:35px auto 0px; font-size:14px; color:#5d5d5d; width:500px; line-height:25px;" >
				© www.parkhere.ca</p>
		</td>
	</tr>
	</tbody>
</table>
</body>
</html>
