<?php 
/*
* Project Name  : Monocle
* Company		: Wave Code Logix
* Author 		: Karthik K
* Created date  : 02 Dec, 2014
* Modified date : 02 Dec, 2014
* Description 	: Email template for new user account created
*/ ?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="viewport" content="width=device-width"/>
		<style type="text/css">
		body{margin: 0}
		</style>
	</head>
	<body>
		<table width='100%'>
			<tr style='box-shadow: 0 1px 10px rgba(0, 0, 0, 0.1);background-color: #fafafa;background-image: linear-gradient(to bottom, #ffffff, #f2f2f2);'>
				<td style='border-bottom:1px solid #d4d4d4'>
					<a href='<?php echo base_url(); ?>'>
						<img src="<?php echo ImageUrl('monocle_logo.png'); ?>">
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<table  width='100%' style='padding:0 20px'>
						<tr>
							<td><h4 style='margin-bottom:0px'>{{NAME}}</h4></td>
							<td align='right'>{{TIME}}</td>
						</tr>
						<tr>
							<td colspan='2'>{{CONTENT}}</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<p style='background: none repeat scroll 0 0 #25c9da;color: #ffffff;padding: 25px;'>© 2014 Monocle Health Data LLC, All Rights Reserved.</p>
				</td>
			</tr>
		</table>
	</body>
</html>