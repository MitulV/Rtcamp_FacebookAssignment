<head>
	<title>The Facebook Challenge</title>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<style type="text/css">
			.login_class
			{
			margin-left: 35%;
			margin-top: 2%;
			width:30%;
			height: 60%;
			text-align: center;
			}		
			.login_img
			{
			margin-top: 3%;
			margin-bottom: 5%;
			}
			.login_button
			{
				margin-top: 5%;
			}
	</style>
</head>
<body>
<?php
include 'header.php';
require 'config.php';
$permissions = ['email,user_photos'];
$loginUrl = $helper->getLoginUrl('http://localhost/Rtcamp_facebook/fb-callback.php', $permissions);
echo '<h1  style="text-align:center;margin-top:2%;"><span class="label label-primary" >Login Here To Get Your Facebook Albums </span></h1><div class="login_class">';
echo '<a style="text-decoration:none;" class="login_button" href="' . htmlspecialchars($loginUrl) . '"><img  class="login_img"src="images/facebook.png"/></a>';
echo '<a style="text-decoration:none;" class="login_button" href="' . htmlspecialchars($loginUrl) . '"><button type="button" class="btn btn-primary btn-lg btn-block">Login with Facebook!</button></a></div>';
?>
</body>