<?php
session_start();
if (!$_SESSION['name_member']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$sql0 = "SELECT * FROM members WHERE Username = '".$_SESSION['name_member']."' OR Email = '".$_SESSION['email_member']."'  ";
$result0 = mysqli_query($conn, $sql0);
$row0 = mysqli_fetch_assoc($result0);

if (empty($_SESSION['profile'])) {
    $picture = "profiles/default.jpg";
}else{
    $picture = $_SESSION['profile'];
}

//Unmatch help request
$sql_unmatch_helprequest = "SELECT SUM(Amount) AS helprequest, COUNT(Amount) AS num_unmatch_helprequest FROM helprequest WHERE Username = '".$_SESSION['name_member']."' AND (Matched = '0' OR Matched IS NULL) ";
$result_unmatch_helprequest = mysqli_query($conn, $sql_unmatch_helprequest);
$row_unmatch_helprequest = mysqli_fetch_assoc($result_unmatch_helprequest);
$total_unmatch_helprequest_amt = $row_unmatch_helprequest['helprequest'];
$total_unmatch_helprequest_num = $row_unmatch_helprequest['num_unmatch_helprequest'];

//Match help request
$sql_match_helprequest = "SELECT SUM(Amount) AS helprequest, COUNT(Amount) AS num_match_helprequest FROM helprequest WHERE Username = '".$_SESSION['name_member']."' AND Matched = '1' ";
$result_match_helprequest = mysqli_query($conn, $sql_match_helprequest);
$row_match_helprequest = mysqli_fetch_assoc($result_match_helprequest);
$total_match_helprequest_amt = $row_match_helprequest['helprequest'];
$total_match_helprequest_num = $row_match_helprequest['num_match_helprequest'];

//Help paid
$sql_helppaid = "SELECT SUM(Amount) AS helppaid, COUNT(Amount) AS num_helppaid FROM helprequest WHERE Matched = '1' AND Username = '".$_SESSION['name_member']."' AND SN IN (SELECT PledgeNo FROM matching WHERE Status = 'Paid' AND ConfirmationDate IS NOT NULL)";
$result_helppaid = mysqli_query($conn, $sql_helppaid);
$row_helppaid = mysqli_fetch_assoc($result_helppaid);
$total_helppaid_amt = $row_helppaid['helppaid'];
$total_helppaid_num = $row_helppaid['num_helppaid'];

//Help to receive
$sql_helptoreceive = "SELECT SUM(Balance) AS helptoreceive, COUNT(Balance) AS num_helptoreceive FROM helptoreceive WHERE PledgeNo IN (SELECT SN FROM helprequest WHERE Username = '".$_SESSION['name_member']."')";
$result_helptoreceive = mysqli_query($conn, $sql_helptoreceive);
$row_helptoreceive = mysqli_fetch_assoc($result_helptoreceive);
$total_helptoreceive_amt = $row_helptoreceive['helptoreceive'];
$total_helptoreceive_num = $row_helptoreceive['num_helptoreceive'];


//Help received
$sql_helpreceived = "SELECT SUM(AmountReceived) AS helpreceived, COUNT(AmountReceived) AS num_helpreceived FROM helpreceived WHERE MatchingNo IN (SELECT SN FROM matching WHERE Status = 'Paid' AND ConfirmationDate IS NOT NULL) AND PledgeNo IN (SELECT SN FROM helprequest WHERE Username = '".$_SESSION['name_member']."')";
$result_helpreceived = mysqli_query($conn, $sql_helpreceived);
$row_helpreceived = mysqli_fetch_assoc($result_helpreceived);
$total_helpreceived_amt = $row_helpreceived['helpreceived'];
$total_helpreceived_num = $row_helpreceived['num_helpreceived'];


//Referal bonus
$sql_referalbonus = "SELECT SUM(Amount) AS referalbonus, COUNT(Amount) AS num_referalbonus FROM referalbonus WHERE Username = '".$_SESSION['name_member']."'";
$result_referalbonus = mysqli_query($conn, $sql_referalbonus);
$row_referalbonus = mysqli_fetch_assoc($result_referalbonus);
$total_referalbonus_amt = $row_referalbonus['referalbonus'];
$total_referalbonus_num = $row_referalbonus['num_referalbonus'];


//Bonus
$sql_bonus = "SELECT SUM(Amount) AS bonus, COUNT(Amount) AS num_bonus FROM bonus WHERE Username = '".$_SESSION['name_member']."'";
$result_bonus = mysqli_query($conn, $sql_bonus);
$row_bonus = mysqli_fetch_assoc($result_bonus);
$total_bonus_amt = $row_bonus['bonus'];
$total_bonus_num = $row_bonus['num_bonus'];


//Downlines
$sql_downlines = "SELECT * FROM members WHERE Username = '".$_SESSION['name_member']."' ";
$result_downlines = mysqli_query($conn, $sql_downlines);
$row_downlines = mysqli_fetch_assoc($result_downlines);
$myreferalid = $row_downlines['MyReferalID'];

$sql_my_downlines = "SELECT * FROM members WHERE ReferalID = '$myreferalid' ";
$result_my_downlines = mysqli_query($conn, $sql_my_downlines);
$count_my_downlines = mysqli_num_rows($result_my_downlines);


//Shared testimony
$sql_testimony = "SELECT * FROM testimony WHERE Username = '".$_SESSION['name_member']."' ";
$result_testimony = mysqli_query($conn, $sql_testimony);
$count_testimony = mysqli_num_rows($result_testimony);
$row_testimony = mysqli_fetch_assoc($result_testimony);

//News
$sql_news = "SELECT * FROM news WHERE Active = '1' ";
$result_news = mysqli_query($conn, $sql_news);
$count_news = mysqli_num_rows($result_news);
$row_news = mysqli_fetch_assoc($result_news);


//Transactions summary
//Dued payment
$sql_matched = "SELECT * FROM matching WHERE Status =  'Not Paid' AND MatchTo = '".$_SESSION['name_member']."' ";
$result_matched = mysqli_query($conn, $sql_matched);
$count_matched = mysqli_num_rows($result_matched);

//Matched payment
$sql_matching = "SELECT * FROM matching WHERE Status =  'Not Paid' AND PledgeNo IN (SELECT SN FROM helprequest WHERE Matched = '1' AND Username = '".$_SESSION['name_member']."' ) ";
$result_matching = mysqli_query($conn, $sql_matching);
$count_matching = mysqli_num_rows($result_matching);

//Unmatched payment
$sql_pledge = "SELECT * FROM helprequest WHERE (Matched IS NULL OR Matched = '0') AND Username = '".$_SESSION['name_member']."' ";
$result_pledge = mysqli_query($conn, $sql_pledge);
$count_pledge = mysqli_num_rows($result_pledge);

//To receive payment
$sql_toreceive = "SELECT * FROM helptoreceive WHERE Balance != '0' AND PledgeNo IN (SELECT SN FROM helprequest WHERE Username = '".$_SESSION['name_member']."') ";
$result_toreceive = mysqli_query($conn, $sql_toreceive);
$count_toreceive = mysqli_num_rows($result_toreceive);

//Total
$count_total = $count_matched + $count_matching + $count_pledge + $count_toreceive;



if (isset($_POST['uploadfile'])){

    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    $id = $_SESSION['name_member'];

    $sn = $_GET['sn'];

    $fileInfo = PATHINFO($_FILES["image"]["name"]);
    if ($fileInfo['extension'] == "jpg" OR $fileInfo['extension'] == "png" OR $fileInfo['extension'] == "jpeg") {
        $newFilename = $sn . "_" . date("Y_m_d H-i-s") . "." . $fileInfo['extension'];
        if (move_uploaded_file($_FILES["image"]["tmp_name"], "../proofs/" . $newFilename)) {
            $location = "proofs/" . $newFilename;

            $sql = "UPDATE matching SET Proof = '$location' WHERE SN = '$sn'";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            if ($result) {
                $mgs_profile_success = "Proof of payment successfully received. We will verify and confirm this and start the count down for someone to help you";
            }else{
                $mgs_profile_error = "Failed to submit proof of payment, please try again";
            }
        }else{
            $mgs_profile_error = "Failed to upload proof of payment, please try again";
        }
    }else{
        $mgs_profile_error = "Photo not uploaded. Please upload JPG or PNG files only";
    }
}

if (isset($_POST['addtime'])){

    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    $hours = mysqli_real_escape_string($conn, $_POST['hours']);

    $id = $_SESSION['name_member'];

    $sn = $_GET['sn'];

    $sql = "SELECT * FROM matching WHERE SN = '$sn' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $lastdate = $row['LastDateToPay'];
    $newlastdate = date('Y-m-d H:i:s', strtotime('+'.$hours.' hours', strtotime($lastdate)));

    $sql1 = "UPDATE matching SET LastDateToPay = '$newlastdate' WHERE SN = '$sn' ";
    $result1 = mysqli_query($conn, $sql1);
    if ($result1) {
        $mgs_time_success = "Successfully added time for payment";
    }else{
        $mgs_time_error = "Failed to add time, please try again";
    }
}


if (isset($_POST['submit'])){

    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    $id = $_SESSION['name_member'];

    $testimony = mysqli_real_escape_string($conn, $_POST['testimony']);

    //Other data
    $date = date("Y-m-d H:i:s");

    $sql = "INSERT INTO testimony (Username, Testimony, SharedDate) VALUES ('$id', '$testimony', '$date') ";
    $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if ($result) {
        $mgs_testimony_success = "Your testimony was successfully shared.";
    }else{
        $mgs_testimony_error = "Failed to share testimony, please try again";
    }
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Testimony - Secure Prime Investment</title>
	<!-- Bootstrap Styles-->
    <link href="/SecurePrimeInvestment/user/assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
    <link href="/SecurePrimeInvestment/user/assets/css/font-awesome.css" rel="stylesheet" />
     <!-- Morris Chart Styles-->
   
        <!-- Custom Styles-->
    <link href="/SecurePrimeInvestment/user/assets/css/custom-styles.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
     <!-- TABLE STYLES-->
    <link href="/SecurePrimeInvestment/user/assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="schoex.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/SecurePrimeInvestment/user/dashboard/"><?php echo "" . $row0['Username'] . ""; ?></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a id="profile" href="#" data-toggle="modal"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a id="reginfo" href="#" data-toggle="modal"><i class="fa fa-file fa-fw"></i> Registration Details</a>
                        </li>
                        <li><a id="settings" href="#" data-toggle="modal"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a id="log-out" href="#" data-toggle="modal"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>

            <!-- Modals -->

            <!-- Logout -->
            <div class="modal fade" id="log-out-modal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <center><h4 class="modal-title" id="myModalLabel">Logging out...</h4></center>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <center><strong><span style="font-size: 15px;">Username: <?php echo $_SESSION['name_member']; ?></span></strong></center>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                            <a href="/SecurePrimeInvestment/user/logout.php" class="btn btn-danger"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="modal fade" id="settings-modal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <form method="POST" action="/SecurePrimeInvestment/user/update_account.php">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <center><h4 class="modal-title" id="myModalLabel">My Account</h4></center>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div style="height: 10px;"></div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Username:</span>
                                        <input type="text" style="width: 100%;" class="form-control" pattern="[a-zA-Z\s]+" disabled="disabled" value="<?php echo " " . $_SESSION['name_member'] . " "; ?>">
                                    </div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Password:</span>
                                        <input type="password" style="width: 100%;" disabled="disabled" class="form-control" value="<?php echo " " . $_SESSION['password'] . " "; ?>">
                                    </div>
                                    <hr>
                                    <span>Change password:</span>
                                    <div style="height: 10px;"></div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">New Password:</span>
                                        <input type="password" style="width: 100%;" class="form-control" name="password1" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
                                    </div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Confirm New Password:</span>
                                        <input type="password" style="width: 100%;" class="form-control" name="password2" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
                                    </div>
                                    <hr>
                                    <span>Enter current password to save changes:</span>
                                    <div style="height: 10px;"></div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Password:</span>
                                        <input type="password" style="width: 100%;" class="form-control" name="password0" required="required">
                                    </div>
                                </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                                <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Registration Info -->
            <div class="modal fade" id="reginfo-modal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <form method="POST" action="/SecurePrimeInvestment/user/update_reginfo.php">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <center><h4 class="modal-title" id="myModalLabel">Registration Details</h4></center>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div style="height: 10px;"></div>
                                    <div class="form-group" style="width: 100%;">
                                        <input type="text" placeholder="First Name" class="form-control" name="firstname" id="example-name" value="<?php echo " " . $row0['FirstName'] . " "; ?>">
                                    </div>
                                    <div class="form-group" style="width: 100%;">
                                        <input type="text" placeholder="Last Name" class="form-control" name="lastname" id="example-name" value="<?php echo " " . $row0['LastName'] . " "; ?>">
                                    </div>
                                    <div class="form-group" style="width: 100%;">
                                        <input type="text" placeholder="Username" class="form-control" name="username" id="example-name" value="<?php echo " " . $row0['Username'] . " "; ?>">
                                    </div>
                                    <div class="form-group" style="width: 100%;">
                                        <input type="email" placeholder="Email" class="form-control" name="email" id="example-name" value="<?php echo " " . $row0['Email'] . " "; ?>">
                                    </div>
                                    <div class="form-group" style="width: 100%;">
                                        <input type="tel" placeholder="Phone" class="form-control" name="phone" id="example-name" value="<?php echo " " . $row0['Phone'] . " "; ?>">
                                    </div>
                                </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                                <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Profile -->
            <div class="modal fade" id="profile-modal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <form method="POST" action="/SecurePrimeInvestment/user/update_photo.php" enctype="multipart/form-data">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <center><h4 class="modal-title" id="myModalLabel">Uploading Photo...</h4></center>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div style="height: 10px;"></div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Username:</span>
                                        <input type="text" style="width: 100%;" class="form-control" pattern="[a-zA-Z\s]+" disabled="disabled" name="usn" value="<?php echo " " . $_SESSION['name_member'] . " "; ?>">
                                    </div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Photo:</span>
                                        <input type="file" style="width: 100%;" class="form-control" name="image" required="required">
                                    </div>
                                </div> 
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                                <button type="submit" name="submit" class="btn btn-success"><span class="glyphicon glyphicon-check"></span> Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">

                    <li>
                        <a href="/SecurePrimeInvestment/user/dashboard/"><i class="fa fa-dashboard"></i>DASHBOARD</a>
                    </li>
                    <li>
                        <a href="/SecurePrimeInvestment/user/momoaccount/"><i class="fa fa-usd"></i>MOBILE MONEY ACCOUNT</a>
                    </li>
                    <li>
                        <a href="/SecurePrimeInvestment/user/newpledge/"><i class="fa fa-plus"></i>MAKE NEW PLEDGE</a>
                    </li>
                    <li>
                        <a href="/SecurePrimeInvestment/user/pledgerequests/"><i class="fa fa-list"></i>LIST OF PLEDGE REQUESTS</a>
                    </li>
                    <li>
                        <a  href="/SecurePrimeInvestment/user/pledgeprovided/"><i class="fa fa-list"></i> LIST OF PLEDGE PAID</a>
                    </li>
                    <li>
                        <a  href="/SecurePrimeInvestment/user/pledgetoreceive/"><i class="fa fa-list"></i> LIST OF PLEDGE TO RECEIVE</a>
                    </li>
                     <li>
                        <a  href="/SecurePrimeInvestment/user/pledgereceived/"><i class="fa fa-list"></i> LIST OF PLEDGE RECEIVED</a>
                    </li>
                    <li>
                        <a  href="/SecurePrimeInvestment/user/investmentrecord/"><i class="fa fa-file"></i> INVESTMENT RECORD</a>
                    </li>
                    <li>
                        <a  class="active-menu" href="/SecurePrimeInvestment/user/testimony/"><i class="fa fa-share"></i> SHARE TESTIMONY</a>
                    </li>
                    <li>
                        <a  href="/SecurePrimeInvestment/user/downlines/"><i class="fa fa-arrow-down"></i> MY DOWNLINES</a>
                    </li>
                    <li>
                        <a  href="/SecurePrimeInvestment/user/referalbonus/"><i class="fa fa-plus-circle"></i> REFERAL BONUS</a>
                    </li>
                    <li>
                        <a href="/SecurePrimeInvestment/user/logout.php"><i class="fa fa-sign-out"></i>LOG OUT</a>
                    </li>
                </ul>
            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" style="background-color: #fff;">
            <div id="page-inner">
			    <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">

                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">

                                <div class="card">
                                    <div class="card-body"> 
                                        <h5>My Testimony</h5>
                                    </div>
                                    <hr class="mb-0"> 
                                    <?php
                                    $sql_testimony = "SELECT * FROM testimony WHERE Username = '".$_SESSION['name_member']."' ";
                                    $result_testimony = mysqli_query($conn, $sql_testimony);
                                    $count_testimony = mysqli_num_rows($result_testimony);
                                    if ($count_testimony == 0) { ?>
                                    <div class="card-body"> 
                                        <form class="forms-sample" action="index.php" method="POST">
                                            <div class="form-group">
                                                <textarea name="testimony" rows="3" placeholder="Write testimony here..." class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" name="submit" class="form-control bg-blue btn btn-primary"><i class="ik ik-gitlab"></i> SHARE MY TESTIMONY</button>
                                            </div>
                                            <?php
                                            if (isset($mgs_testimony_error)) { ?>
                                                <div class="alert alert-danger" role="alert">
                                                    <?php echo "" . $mgs_testimony_error . ""; ?>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            if (isset($mgs_testimony_success)) { ?>
                                                <div class="alert alert-success" role="alert">
                                                    <h1><?php echo "" . $mgs_testimony_success . ""; ?></h1>
                                                </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                    <?php }else{
                                        $row_testimony = mysqli_fetch_assoc($result_testimony);
                                     ?>
                                    <div class="card-body"> 
                                        <div class="text" style="color: blue;">
                                            <h6><?php echo "" . $row_testimony['Testimony'] . ""; ?></h6>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>


                            </div>
                        </div>

                        <br>
                        <div class="row">
                        <?php
                        $sql_matched = "SELECT * FROM matching WHERE Status =  'Not Paid' AND MatchTo = '".$_SESSION['name_member']."' ";
                        $result_matched = mysqli_query($conn, $sql_matched);
                        while ($row_matched = mysqli_fetch_assoc($result_matched)) {
                            $sql_momo_details = "SELECT * FROM momoaccount WHERE Username = '".$row_matched['MatchTo']."' ";
                            $result_momo_details = mysqli_query($conn, $sql_momo_details);
                            $row_momo_details = mysqli_fetch_assoc($result_momo_details);

                            $sql_amt_details = "SELECT * FROM helprequest WHERE SN = '".$row_matched['PledgeNo']."' ";
                            $result_amt_details = mysqli_query($conn, $sql_amt_details);
                            $row_amt_details = mysqli_fetch_assoc($result_amt_details);

                            $sql_user_details = "SELECT * FROM members WHERE Username = '".$row_amt_details['Username']."' ";
                            $result_user_details = mysqli_query($conn, $sql_user_details);
                            $row_user_details = mysqli_fetch_assoc($result_user_details);
                         ?>
                            <div class="col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6> YOUR PAID HELP REQUEST IS DUED AND HAS BEEN MATCHED. </h6>
                                    </div>
                                    <div class="card-body todo-task"> 
                                        <div class="dd" data-plugin="nestable">
                                            <ol class="dd-list">
                                                <li class="dd-item" data-id="1">
                                                    <div class="dd-handle">
                                                        <h6>Help Request <strong>#<?php echo "" . $row_matched['PledgeNo'] . ""; ?></strong></h6>
                                                        <p>You are to receive payment from <br> <strong style="text-align: center;"> <?php echo "" . $row_amt_details['Username'] . ""; ?> (<?php echo "" . $row_user_details['Phone'] . ""; ?>)</strong> .</p>
                                                        <p>Amount: <strong><?php echo "CFA " . number_format($row_amt_details['Amount'], 2, '.', ',') . ""; ?></strong></p>
                                                    </div>
                                                </li>
                                                <li class="dd-item" data-id="1">
                                                    <div class="dd-handle">
                                                        <h6>Last date to pay for him/her <strong><?php echo "" . $row_matched['LastDateToPay'] . ""; ?></strong></h6>
                                                        <?php
                                                        $lastdate = strtotime($row_matched['LastDateToPay']);
                                                        $remaining = $lastdate - time();
                                                        $days_remaining = floor($remaining / 86400);
                                                        $hours_remaining = floor(($remaining % 86400) / 3600);
                                                        $minutes_remaining = floor(($remaining % 3600) / 60);
                                                        $seconds_remaining = ($remaining % 60);
                                                        ?>
                                                        <p class="alert-success" style="text-align: center;"><span id="countdown" class="timer"></span></p>
                                                    </div>
                                                </li>
                                                <li class="dd-item" data-id="1">
                                                    <div class="dd-handle">
                                                        <form class="forms-sample" action="index.php?sn=<?php echo "" . $row_matched['SN'] . ""; ?>" method="POST">
                                                            <div class="form-group">
                                                                <label>Add more time (in hours)</label>
                                                                <div class="input-group col-xs-12">
                                                                    <input type="number" name="hours" min="0" class="form-control" required>
                                                                    <span class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-primary" type="submit" name="addtime">Add Time</button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            if (isset($mgs_time_error)) { ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <?php echo "" . $mgs_time_error . ""; ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php
                                                            if (isset($mgs_time_success)) { ?>
                                                                <div class="alert alert-success" role="alert">
                                                                    <?php echo "" . $mgs_time_success . ""; ?>
                                                                </div>
                                                            <?php } ?>
                                                        </form>
                                                        <a href="confirm.php?id=<?php echo "" . $row_matched['SN'] . ""; ?>&user=<?php echo "" . $row_amt_details['Username'] . ""; ?>&pledgeno0=<?php echo "" . $row_matched['MatchToNo'] . ""; ?>&pledgeno1=<?php echo "" . $row_matched['PledgeNo'] . ""; ?>&amount0=<?php echo "" . $row_amt_details['Amount'] . ""; ?>"><button type="button" class="bg-primary btn btn-primary"><i class="ik ik-check-circle"></i> CONFIRM PAYMENT</button></a>
                                                    </div>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <br>
                        <div class="row">
                        <?php
                        $sql_matching = "SELECT * FROM matching WHERE Status =  'Not Paid' AND PledgeNo IN (SELECT SN FROM helprequest WHERE Matched = '1' AND Username = '".$_SESSION['name_member']."' ) ";
                        $result_matching = mysqli_query($conn, $sql_matching);
                        while ($row_matching = mysqli_fetch_assoc($result_matching)) {
                            $sql_momo_details = "SELECT * FROM momoaccount WHERE Username = '".$row_matching['MatchTo']."' ";
                            $result_momo_details = mysqli_query($conn, $sql_momo_details);
                            $row_momo_details = mysqli_fetch_assoc($result_momo_details);

                            $sql_amt_details = "SELECT * FROM helprequest WHERE SN = '".$row_matching['PledgeNo']."' ";
                            $result_amt_details = mysqli_query($conn, $sql_amt_details);
                            $row_amt_details = mysqli_fetch_assoc($result_amt_details);
                         ?>
                            <div class="col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6> YOUR PENDING HELP REQUEST HAS BEEN MATCHED </h6>
                                    </div>
                                    <div class="card-body todo-task"> 
                                        <div class="dd" data-plugin="nestable">
                                            <ol class="dd-list">
                                                <li class="dd-item" data-id="1">
                                                    <div class="dd-handle">
                                                        <h6>Help Request <strong>#<?php echo "" . $row_matching['PledgeNo'] . ""; ?></strong></h6>
                                                        <p>Your request to provide help has been matched. You are to pay <strong><?php echo "" . $row_matching['MatchTo'] . ""; ?></strong>.</p>
                                                        <p>Mobile Money Carrier: <strong><?php echo "" . $row_momo_details['MomoCarrier'] . ""; ?></strong></p>
                                                        <p>Mobile Money Name: <strong><?php echo "" . $row_momo_details['MomoName'] . ""; ?></strong></p>
                                                        <p>Mobile Money Number: <strong><?php echo "" . $row_momo_details['MomoNumber'] . ""; ?></strong></p>
                                                        <p>Amount: <strong><?php echo "CFA " . number_format($row_amt_details['Amount'], 2, '.', ',') . ""; ?></strong></p></p>
                                                        <p>You have <strong>24hrs</strong> to make and submit proof of payment.</p>
                                                    </div>
                                                </li>
                                                <li class="dd-item" data-id="1">
                                                    <div class="dd-handle">
                                                        <h6>Expires on  <strong><?php echo "" . $row_matching['LastDateToPay'] . ""; ?></strong></h6>
                                                        <?php
                                                        $lastdate0 = strtotime($row_matching['LastDateToPay']);
                                                        $remaining0 = $lastdate0 - time();
                                                        $days_remaining0 = floor($remaining0 / 86400);
                                                        $hours_remaining0 = floor(($remaining0 % 86400) / 3600);
                                                        $minutes_remaining0 = floor(($remaining0 % 3600) / 60);
                                                        $seconds_remaining0 = ($remaining0 % 60);
                                                        ?>
                                                        <p class="alert-success" style="text-align: center;"><span id="countdown0" class="timer"></span></p>
                                                    </div>
                                                </li>
                                                <li class="dd-item" data-id="1">
                                                    <div class="dd-handle">
                                                        <form class="forms-sample" action="index.php?sn=<?php echo "" . $row_matching['SN'] . ""; ?>" method="POST" enctype="multipart/form-data">
                                                            <div class="form-group">
                                                                <label>Proof of payment</label>
                                                                <div class="input-group col-xs-12">
                                                                    <input type="file" name="image" class="form-control file-upload-info" required>
                                                                    <span class="input-group-append">
                                                                    <button class="file-upload-browse btn btn-primary" type="submit" name="uploadfile">Upload</button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            if (isset($mgs_profile_error)) { ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <?php echo "" . $mgs_profile_error . ""; ?>
                                                                </div>
                                                            <?php } ?>
                                                            <?php
                                                            if (isset($mgs_profile_success)) { ?>
                                                                <div class="alert alert-success" role="alert">
                                                                    <?php echo "" . $mgs_profile_success . ""; ?>
                                                                </div>
                                                            <?php } ?>
                                                        </form>
                                                        <a href="blockaccount.php"><button type="button" class="bg-red btn btn-danger"><i class="ik ik-trash"></i> I CANNOT PAY</button></a>
                                                    </div>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                        <br>
                        <div class="row">
                        <?php
                        $sql_pledge = "SELECT * FROM helprequest WHERE (Matched IS NULL OR Matched = '0') AND Username = '".$_SESSION['name_member']."' ";
                        $result_pledge = mysqli_query($conn, $sql_pledge);
                        while ($row_pledge = mysqli_fetch_assoc($result_pledge)) { ?>
                            <div class="col-lg-6 col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h6> YOU HAVE PENDING UNMATCHED HELP REQUEST </h6>
                                    </div>
                                    <div class="card-body todo-task"> 
                                        <div class="dd" data-plugin="nestable">
                                            <ol class="dd-list">
                                                <li class="dd-item" data-id="1">
                                                    <div class="dd-handle">
                                                        <h6>Help Request <strong>#<?php echo "" . $row_pledge['SN'] . ""; ?></strong></h6>
                                                        <p>Your request to provide help has been received. You will be matched to provide help to another participant.</p>
                                                        <p>An email will be sent to you once the matching has been made. However, kindly signin to your dashboard daily to check if the matching has been made incase there is a delay or network issue in receiving the email</p>
                                                    </div>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="list-item-wrap">
                                    <div class="list-item">
                                        <div class="item-inner">
                                            <?php
                                            if ($count_testimony == 0) { ?>
                                                <div class="list-title" style="text-align: center; color: red; font-size: 20px;">No Shared Testimony Yet.</div>
                                            <?php }else{ ?>
                                                <div class="list-title" style="text-align: center; color: blue; font-size: 20px;"><?php echo "" . $row_testimony['Testimony'] . ""; ?>.</div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <footer class="footer">
                            <div class="w-100 clearfix">
                                <span style="float: left;" class="text-center text-sm-left d-md-inline-block">Copyright © <?php echo date("Y"); ?> Secure Prime Investment All Rights Reserved.</span>
                                <span style="float: right;" class="float-none float-sm-right mt-1 mt-sm-0 text-center">Secure Prime Investment</span>
                            </div>
                        </footer>





                    </div>
                </div> 
            </div>
        </div>
    </div>

    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="/SecurePrimeInvestment/user/assets/js/jquery-1.10.2.js"></script>
      <!-- Bootstrap Js -->
    <script src="/SecurePrimeInvestment/user/assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="/SecurePrimeInvestment/user/assets/js/jquery.metisMenu.js"></script>
     <!-- DATA TABLE SCRIPTS -->
    <script src="/SecurePrimeInvestment/user/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="/SecurePrimeInvestment/user/assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                
            });
        });


        $("#log-out").click(function(){
            $("#log-out-modal").modal();
        });
    
        $("#settings").click(function(){
            $("#settings-modal").modal();
        });
   
        $("#profile").click(function(){
            $("#profile-modal").modal();
        });
   
        $("#reginfo").click(function(){
            $("#reginfo-modal").modal();
        });

        $("#add").click(function(){
            $("#add-modal").modal();
        });
   
        $("#edit").click(function(){
            $("#edit-modal").modal();
        });
   
        $("#delete").click(function(){
            $("#delete-modal").modal();
        });
    </script>
        <script type="text/javascript">
            var initialTime = <?php echo "$remaining"; ?>;

            var seconds = initialTime;
            function timer() {
                var days        = Math.floor(seconds/24/60/60);
                var hoursLeft   = Math.floor((seconds) - (days*86400));
                var hours       = Math.floor(hoursLeft/3600);
                var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
                var minutes     = Math.floor(minutesLeft/60);
                var remainingSeconds = seconds % 60;
                if (remainingSeconds < 10) {
                    remainingSeconds = "0" + remainingSeconds; 
                }
                document.getElementById('countdown').innerHTML = days + " days " + hours + " hours " + minutes + " minutes " + remainingSeconds+ " seconds";
                if (seconds == 0) {
                    clearInterval(countdownTimer);
                    document.getElementById('countdown').innerHTML = "Expired";
                } else {
                    seconds--;
                }
            }
            var countdownTimer = setInterval('timer()', 1000);
        </script>

        <script type="text/javascript">
            var initialTime0 = <?php echo "$remaining0"; ?>;

            var seconds0 = initialTime0;
            function timer0() {
                var days0        = Math.floor(seconds0/24/60/60);
                var hours0Left0   = Math.floor((seconds0) - (days0*86400));
                var hours0       = Math.floor(hours0Left0/3600);
                var minutes0Left0 = Math.floor((hours0Left0) - (hours0*3600));
                var minutes0     = Math.floor(minutes0Left0/60);
                var remainingSeconds0 = seconds0 % 60;
                if (remainingSeconds0 < 10) {
                    remainingSeconds0 = "0" + remainingSeconds0; 
                }
                if (days0 < 0) {
                    window.location.href = "blockaccount.php";
                }
                document.getElementById('countdown0').innerHTML = days0 + " days " + hours0 + " hours " + minutes0 + " minutes " + remainingSeconds0+ " seconds";
                if (seconds0 == 0) {
                    clearInterval(countdownTimer0);
                    document.getElementById('countdown0').innerHTML = "Expired";
                    window.location.href = "blockaccount.php";
                } else {
                    seconds0--;
                }
            }
            var countdownTimer0 = setInterval('timer0()', 1000);
        </script>
        <script type="text/javascript">
            var initialTime00 = <?php echo "$remaining00"; ?>;

            var seconds00 = initialTime00;
            function timer00() {
                var days00        = Math.floor(seconds00/24/60/60);
                var hours00Left00   = Math.floor((seconds00) - (days00*86400));
                var hours00       = Math.floor(hours00Left00/3600);
                var minutes0Left00 = Math.floor((hours00Left00) - (hours00*3600));
                var minutes00     = Math.floor(minutes0Left00/60);
                var remainingSeconds00 = seconds00 % 60;
                if (remainingSeconds00 < 10) {
                    remainingSeconds00 = "0" + remainingSeconds00; 
                }
                document.getElementById('countdown00').innerHTML = days00 + " days " + hours00 + " hours " + minutes00 + " minutes " + remainingSeconds00+ " seconds";
                if (seconds00 == 0) {
                    clearInterval(countdownTimer00);
                    document.getElementById('countdown00').innerHTML = "Dued";
                } else {
                    seconds00--;
                }
            }
            var countdownTimer00 = setInterval('timer00()', 1000);
        </script>
    
   
</body>
</html>
