<?php
if (isset($_POST['signin'])) {
	//Establish connection to DB
	$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

	//Get form data
	$name = mysqli_real_escape_string($conn, $_POST['name']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);

	//Other data
	$date = date("Y-m-d H:i:s");

	//Check contrainst
	//Check if name is a valid user
	$sql = "SELECT * FROM users WHERE Username = '$name' OR Email = '$name'";
	$result = mysqli_query($conn, $sql);
	$resultCheck = mysqli_num_rows($result);
	if ($resultCheck != 1) {
		$SESSION['message'] =  "Invalid username or email! Please try again";
	}else{
		$row = mysqli_fetch_assoc($result);
		if ($row['Active'] == 0) {
			$SESSION['message'] =  "You are not an active user of this system. Your account has been blocked or deleted";
		}elseif ($row['Active'] == 1) {
			//Check password
			$check = password_verify($_POST['password'], $row['Password']);
			if ($check == false) {
			    $SESSION['message'] =  "Wrong Password";
		    }elseif ($check == true) {
		    	if ($row['Status'] == 'Administrator') {
					session_start();
		        	$_SESSION['name_admin'] = $row['Username'];
		        	$_SESSION['email_admin'] = $row['Email'];
		        	$_SESSION['password'] = $row['Password'];
		        	$_SESSION['profile'] = $row['Profile'];
		        	$_SESSION['regdate'] = $row['RegistrationDate'];
		        	header("Location: admin/dashboard/");
					exit();
				}elseif ($row['Status'] == 'Member') {
					session_start();
		        	$_SESSION['name_member'] = $row['Username'];
		        	$_SESSION['email_member'] = $row['Email'];
		        	$_SESSION['password'] = $row['Password'];
		        	$_SESSION['profile'] = $row['Profile'];
		        	$_SESSION['regdate'] = $row['RegistrationDate'];
		        	header("Location: user/dashboard/");
					exit();
				}
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<title>Account | Simple Home Investments :: Home of Premium Fund Community Network</title>
<!-- for-mobile-apps -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Simple Home Investments is a Premium Fund Community Network designed to help individuals attain already set goals." />
<meta name="keywords" content="Simple Home Investments, P2P, donation, platform, 100% return of donation in hours, P2P donation, p2p platform, naira, cash, ponzi scheme, nairaland, pyramid scheme, Simple Home Investments Registration, Simple Home Investments login, about Simple Home Investments, best ponzi scheme, latest ponzi scheme, Kenya, Kenya ponzi scheme, Donation, Fund raising, Giving and Receiving, Charity, Community, Wealth Creation, Financial Freedon, Time Freedom, Making Money, Money Making Machine, Helpers" />
<!-- //for-mobile-apps -->

<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Lato:400,700,900,300' rel='stylesheet' type='text/css'>
<link href="css/style-login.css" rel="stylesheet" type="text/css" media="all" />
<script src="js/jquery-1.11.1.min.js"></script> 

<script src="js/jquery2.0.3.min.js"></script>
<script type="text/javascript" src="js/jquery.countdown.min.js"></script>
<script type="text/javascript" src="js/app.js"></script>

<!-- //js -->
<!-- chart -->
<script src="js/Chart.html"></script>
<!-- //chart -->
<script src="js/easyResponsiveTabs.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function () {
			$('#horizontalTab').easyResponsiveTabs({
				type: 'default', //Types: default, vertical, accordion           
				width: 'auto', //auto or any width like 600px
				fit: true   // 100% fit in a container
			});
		});
	   </script>
</head>
<body>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5d7c0c099f6b7a4457e19707/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<div id="ytWidget"></div><script src="../translate.yandex.net/website-widget/v1/widget80d6.js?widgetId=ytWidget&amp;pageLang=en&amp;widgetTheme=light&amp;autoMode=false" type="text/javascript"></script>
<div class="content">
	<h1>Create Account or Login</h1>
	
			
		<div class="main">
			<div class="profile-left wthree">
				<div class="sap_tabs">
				<div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
					<ul class="resp-tabs-list">
						<li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Sign In</span></li>
						<li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><h2><span>Sign Up</span></h2></li>
						<div class="clear"> </div>
					</ul>			
					<div class="resp-tabs-container">
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
						<div class="got">
							<!--login/sigup error messages should be displayed here-->
							<h6>Got an account? Enter your details below to login</h6>
						</div>
							<div class="login-top">
								<form method="POST" action="account.php">
									<input type="text" class="email" name="name" id="signin_username" placeholder="Your Username" required="">
									<input type="password" class="password" name="password" id="signin_password" placeholder="Password" required="">
									
									<div style="color: red; font-size: 15px;">
										<center>
										<?php
											if(isset($SESSION['message'])){
												echo $SESSION['message'];
												unset($SESSION['message']);
											}
										?>
										</center>
									</div>
									<br>

									<input type="checkbox" name="keepin" id="brand" value="">
									<label for="brand"><span></span> Remember me?</label>
									<div class="login-bottom">
										<ul>
											<li>
													<input name="signin" type="submit" value="LOGIN"/>
											</li>
											<li>
												<a href="#">Forgot password?</a>
											</li>
										<ul>
										<div class="clear"></div>
									</div>
								</form>	
							</div>
						</div>
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">
							<div class="login-top sign-top">
								<form method="POST" id="reg-form">

									<input type="text" name="firstname" id="firstname" placeholder="First Name" required="" value="">
									<input type="text" name="lastname" id="lastname" placeholder="Last Name" required="" value="">
									<input type="text" class="email" name="email" id="email" placeholder="Your Email" required="" value="">
									<input type="text" name="phone" id="phone" placeholder="Phone No. e.g 650193182" pattern="[6]{1}[0-9]{8}" title="Phone number should be of 9 digits and must begin with a 6" required="" value="">
									<input type="text" class="name active" name="referalid" id="referalid" placeholder="Referral Id" value="" >
									<input type="text" name="username" id="username" placeholder="Username" required="" value="">
									<input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" class="password" name="password" id="password" placeholder="Password" required="">
									<input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" class="password" name="conpassword" id="conpassword" placeholder="Confirm Password" required="">

										<div>
											<div class="result" id="result"></div>
										</div>


									<div class="login-bottom">
										<ul>
											<li>
													<input name="signup" type="submit" value="Create Account">
											</li>
										<ul>
										<div class="clear"></div>
									</div>
								</form>
							</div>
						</div>
					</div>	
				</div>
				<div class="clear"> </div>
			</div>
			</div>
			<div class="clear"> </div>
	</div>	
	<p class="footer"> © 2019 Simple Home Investments. All rights reserved</p>
</div>
<!-- for bootstrap working -->
	<script src="js/bootstrap.js"></script>
<!-- //for bootstrap working -->
<script type="text/javascript">
    
        $(document).on('submit', '#reg-form', function()
         {  
          $.post('signup.php', $(this).serialize(), function(data)
          {
           $(".result").html(data);  
           $("#form1")[0].reset();
          // $("#check").reset();

          });
          
          return false;
          
        
        })
</script>
</body>

</html>
