<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

$sql0 = "SELECT * FROM users WHERE Username = '".$_SESSION['name_admin']."' OR Email = '".$_SESSION['email_admin']."'  ";
$result0 = mysqli_query($conn, $sql0);
$row0 = mysqli_fetch_assoc($result0);

if (empty($_SESSION['profile'])) {
    $picture = "profiles/default.jpg";
}else{
    $picture = $_SESSION['profile'];
}

//Members
$sql_members = "SELECT * FROM members";
$result_members = mysqli_query($conn, $sql_members);
$count_members = mysqli_num_rows($result_members);


//Momo Account
$sql_momoaccount = "SELECT * FROM momoaccount ";
$result_momoaccount = mysqli_query($conn, $sql_momoaccount);
$count_momoaccount = mysqli_num_rows($result_momoaccount);

//Unmatch help request
$sql_helprequest_unmatch = "SELECT SUM(Amount) AS helprequest, COUNT(Amount) AS num_helprequest_unmatch FROM helprequest WHERE Matched = '0' OR Matched IS NULL ";
$result_helprequest_unmatch = mysqli_query($conn, $sql_helprequest_unmatch);
$row_helprequest_unmatch = mysqli_fetch_assoc($result_helprequest_unmatch);
$total_helprequest_unmatch_amt = $row_helprequest_unmatch['helprequest'];
$total_helprequest_unmatch_num = $row_helprequest_unmatch['num_helprequest_unmatch'];


//Match help request
$sql_helprequest_match = "SELECT SUM(Amount) AS helprequest, COUNT(Amount) AS num_helprequest_match FROM helprequest WHERE Matched = '1'";
$result_helprequest_match = mysqli_query($conn, $sql_helprequest_match);
$row_helprequest_match = mysqli_fetch_assoc($result_helprequest_match);
$total_helprequest_match_amt = $row_helprequest_match['helprequest'];
$total_helprequest_match_num = $row_helprequest_match['num_helprequest_match'];


//Help paid
$sql_helppaid = "SELECT SUM(Amount) AS helppaid, COUNT(Amount) AS num_helppaid FROM helprequest WHERE Matched = '1' AND SN IN (SELECT PledgeNo FROM matching WHERE Status = 'Paid' AND ConfirmationDate IS NOT NULL)";
$result_helppaid = mysqli_query($conn, $sql_helppaid);
$row_helppaid = mysqli_fetch_assoc($result_helppaid);
$total_helppaid_amt = $row_helppaid['helppaid'];
$total_helppaid_num = $row_helppaid['num_helppaid'];

//Help to receive
$sql_helptoreceive = "SELECT SUM(Balance) AS helptoreceive, COUNT(Balance) AS num_helptoreceive FROM helptoreceive WHERE Balance != '0' AND PledgeNo IN (SELECT SN FROM helprequest)";
$result_helptoreceive = mysqli_query($conn, $sql_helptoreceive);
$row_helptoreceive = mysqli_fetch_assoc($result_helptoreceive);
$total_helptoreceive_amt = $row_helptoreceive['helptoreceive'];
$total_helptoreceive_num = $row_helptoreceive['num_helptoreceive'];


//Help received
$sql_helpreceived = "SELECT SUM(AmountReceived) AS helpreceived, COUNT(AmountReceived) AS num_helpreceived FROM helpreceived WHERE MatchingNo IN (SELECT SN FROM matching WHERE Status = 'Paid' AND ConfirmationDate IS NOT NULL) AND PledgeNo IN (SELECT SN FROM helprequest)";
$result_helpreceived = mysqli_query($conn, $sql_helpreceived);
$row_helpreceived = mysqli_fetch_assoc($result_helpreceived);
$total_helpreceived_amt = $row_helpreceived['helpreceived'];
$total_helpreceived_num = $row_helpreceived['num_helpreceived'];


//Referal bonus
$sql_referalbonus = "SELECT SUM(Amount) AS referalbonus, COUNT(Amount) AS num_referalbonus FROM referalbonus ";
$result_referalbonus = mysqli_query($conn, $sql_referalbonus);
$row_referalbonus = mysqli_fetch_assoc($result_referalbonus);
$total_referalbonus_amt = $row_referalbonus['referalbonus'];
$total_referalbonus_num = $row_referalbonus['num_referalbonus'];


//Bonus
$sql_bonus = "SELECT SUM(Amount) AS bonus, COUNT(Amount) AS num_bonus FROM bonus ";
$result_bonus = mysqli_query($conn, $sql_bonus);
$row_bonus = mysqli_fetch_assoc($result_bonus);
$total_bonus_amt = $row_bonus['bonus'];
$total_bonus_num = $row_bonus['num_bonus'];


//Downlines
$sql_downlines = "SELECT * FROM members WHERE ReferalID IS NOT NULL ";
$result_downlines = mysqli_query($conn, $sql_downlines);
$count_downlines = mysqli_num_rows($result_downlines);


//Shared testimony
$sql_testimony = "SELECT * FROM testimony ";
$result_testimony = mysqli_query($conn, $sql_testimony);
$count_testimony = mysqli_num_rows($result_testimony);



//Transactions summary
//Dued payment
$sql_matched = "SELECT * FROM matching WHERE Status =  'Not Paid' ";
$result_matched = mysqli_query($conn, $sql_matched);
$count_matched = mysqli_num_rows($result_matched);

//Matched payment
$sql_matching = "SELECT * FROM matching WHERE Status =  'Not Paid' AND PledgeNo IN (SELECT SN FROM helprequest WHERE Matched = '1' ) ";
$result_matching = mysqli_query($conn, $sql_matching);
$count_matching = mysqli_num_rows($result_matching);

//Unmatched payment
$sql_pledge = "SELECT * FROM helprequest WHERE (Matched IS NULL OR Matched = '0') ";
$result_pledge = mysqli_query($conn, $sql_pledge);
$count_pledge = mysqli_num_rows($result_pledge);

//To receive payment
$sql_toreceive = "SELECT * FROM helptoreceive WHERE Balance != '0' ";
$result_toreceive = mysqli_query($conn, $sql_toreceive);
$count_toreceive = mysqli_num_rows($result_toreceive);

//Total
$count_total = $count_matched + $count_matching + $count_pledge + $count_toreceive;

?>
<!DOCTYPE html>
<html>
<head>
	<title>Downlines and Uplines - Secure Prime Investment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS files -->
    <link href="/SecurePrimeInvestment/admin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/SecurePrimeInvestment/admin/bower_components/css/print.css" rel="stylesheet" type="text/css">
    <link href="/SecurePrimeInvestment/admin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/SecurePrimeInvestment/admin/bower_components/css/print.css" rel="stylesheet" type="text/css">
    <link href="/SecurePrimeInvestment/admin/css/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/css/awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/SecurePrimeInvestment/admin/css/css-font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/SecurePrimeInvestment/admin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/SecurePrimeInvestment/admin/dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="schoex.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/SecurePrimeInvestment/admin/dashboard/index.php">Secure Prime Investment  </a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-usd fa-fw"></i> <span class="badge bg-danger"> <?php echo "" . $total_helptoreceive_num . ""; ?></span> <i class="fa fa-caret-down"></i> 
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <?php
                        $sql_helptoreceive0 = "SELECT * FROM helptoreceive WHERE Balance != '0' AND PledgeNo IN (SELECT SN FROM helprequest)";
						$result_helptoreceive0 = mysqli_query($conn, $sql_helptoreceive0);
						while ($row_helptoreceive0 = mysqli_fetch_assoc($result_helptoreceive0)) {
							$sql00 = "SELECT * FROM helprequest WHERE SN = '".$row_helptoreceive0['PledgeNo']."' ";
							$result00 = mysqli_query($conn, $sql00);
							$row00 = mysqli_fetch_assoc($result00);
                        ?>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgetoreceive/">
                                <div>
                                    <span class="pull-right text-muted">
                                        <em><?php echo " " . $row_helptoreceive0['LastDateToReceive'] . ""; ?></em>
                                    </span>
                                </div>
                                <div><br><?php echo "CFA " . number_format($row_helptoreceive0['Balance'], 2, '.', ',') . ""; ?></div>
                                <div><strong><?php echo " " . $row00['Username'] . ""; ?></strong></div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <?php } ?>
                        <li>
                            <a class="text-center" href="/SecurePrimeInvestment/admin/pledgetoreceive/">
                                <strong>View All Those To Receive Payment</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i> <span class="badge bg-danger"> <?php echo "" . $total_helprequest_unmatch_num . ""; ?></span> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                    	<?php
                        $sql000 = "SELECT * FROM helprequest WHERE Matched = '0' OR Matched IS NULL";
                        $result000 = mysqli_query($conn, $sql000);
                        while ($row000 = mysqli_fetch_assoc($result000)) { 
                        ?>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgerequests/">
                                <div>
                                    <span class="pull-right text-muted">
                                        <em><?php echo " " . $row000['PledgeDate'] . ""; ?></em>
                                    </span>
                                </div>
                                <div><br><?php echo "CFA " . number_format($row000['Amount'], 2, '.', ',') . ""; ?></div>
                                <div><strong><?php echo " " . $row000['Username'] . ""; ?></strong></div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <?php } ?>
                        <li>
                            <a class="text-center" href="/SecurePrimeInvestment/admin/pledgerequests/">
                                <strong>See All Unmatched Pledge Request</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-tasks -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <span class="badge bg-danger"> <?php echo "" . $count_total . ""; ?></span> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgetoreceive/">
                                <div>
                                    <i class="fa fa-bell fa-fw"></i> To Receive Payments
                                    <span class="pull-right text-muted small">You have <strong><?php echo "" . $count_toreceive . ""; ?></strong> members waiting to receive payment. Please click to obtain more information. <br></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgereceived/">
                                <div>
                                    <i class="fa fa-bell fa-fw"></i> Dued payment
                                    <span class="pull-right text-muted small">You have <strong><?php echo "" . $count_matched . ""; ?></strong> dued payment ready to be matched. Please click to obtain more information. <br> </span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgeprovided/">
                                <div>
                                    <i class="fa fa-bell fa-fw"></i> Matched Payment
                                    <span class="pull-right text-muted small">You have <strong><?php echo "" . $count_matching . ""; ?></strong> matched payment. Please click to obtain more information.<br></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgerequests/">
                                <div>
                                    <i class="fa fa-bell fa-fw"></i> Unmatched Payment
                                    <span class="pull-right text-muted small">You have <strong><?php echo "" . $count_pledge . ""; ?></strong> unmatched help request. Please click to obtain more information.<br></span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="/SecurePrimeInvestment/admin/pledgereceived/">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
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
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">  
                        <li>
                            <a href="/SecurePrimeInvestment/admin/dashboard/index.php"><i class="fa fa-dashboard fa-fw"></i> DASHBOARD</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/users/index.php"><i class="fa fa-users fa-fw"></i> MEMBERS</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/packages/index.php"><i class="fa fa-usd fa-fw"></i> PLEDGE PACKAGES</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/momoaccount/index.php"><i class="fa fa-credit-card fa-fw"></i> MOBILE MONEY ACCOUNTS</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgerequests/index.php"><i class="fa fa-list fa-fw"></i> PLEDGE REQUESTS</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgetoreceive/index.php"><i class="fa fa-list fa-fw"></i> PLEDGE TO RECEIVE</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgeprovided/index.php"><i class="fa fa-list fa-fw"></i> PLEDGE PROVIDED</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/pledgereceived/index.php"><i class="fa fa-list fa-fw"></i> PLEDGE RECEIVED</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/investmentrecord/index.php"><i class="fa fa-file fa-fw"></i> INVESTMENT RECORD</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/testimony/index.php"><i class="fa fa-share fa-fw"></i> SHARED TESTIMONY</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/sendemails/index.php"><i class="fa fa-envelope fa-fw"></i> SEND EMAILS</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/downlines/index.php"><i class="fa fa-arrow-down fa-fw"></i> DOWNLINES</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/referalbonus/index.php"><i class="fa fa-plus-circle fa-fw"></i> REFERAL BONUS</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/publishnews/index.php"><i class="fa fa-globe fa-fw"></i> PUBLISH NEWS</a>
                        </li>
                        <li>
                            <a href="/SecurePrimeInvestment/admin/logout.php"><i class="fa fa-sign-out fa-fw"></i> LOG OUT</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

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
                                <center><strong><span style="font-size: 15px;">Username: <?php echo $_SESSION['name_admin']; ?></span></strong></center>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                            <a href="/SecurePrimeInvestment/admin/logout.php" class="btn btn-danger"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="modal fade" id="settings-modal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <form method="POST" action="/SecurePrimeInvestment/admin/update_account.php">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <center><h4 class="modal-title" id="myModalLabel">My Account</h4></center>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div style="height: 10px;"></div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Username:</span>
                                        <input type="text" style="width: 100%;" class="form-control" pattern="[a-zA-Z\s]+" disabled="disabled" value="<?php echo " " . $_SESSION['name_admin'] . " "; ?>">
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
                        <form method="POST" action="/SecurePrimeInvestment/admin/update_reginfo.php">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <center><h4 class="modal-title" id="myModalLabel">Registration Details</h4></center>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div style="height: 10px;"></div>
                                    <div class="form-group" style="width: 100%;">
                                        <input type="text" placeholder="Username" class="form-control" name="username" id="example-name" value="<?php echo " " . $row0['Username'] . " "; ?>">
                                    </div>
                                    <div class="form-group" style="width: 100%;">
                                        <input type="email" placeholder="Email" class="form-control" name="email" id="example-name" value="<?php echo " " . $row0['Email'] . " "; ?>">
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
                        <form method="POST" action="/SecurePrimeInvestment/admin/update_photo.php" enctype="multipart/form-data">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <center><h4 class="modal-title" id="myModalLabel">Uploading Photo...</h4></center>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div style="height: 10px;"></div>
                                    <div class="form-group input-group" style="width: 100%;">
                                        <span class="input-group-addon" style="width: 70px;">Username:</span>
                                        <input type="text" style="width: 100%;" class="form-control" pattern="[a-zA-Z\s]+" disabled="disabled" name="usn" value="<?php echo " " . $_SESSION['name_admin'] . " "; ?>">
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

        <div id="page-wrapper">
        	<br>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        </div>
                        <div class="panel-body">
                        	<div class="dataTable_wrapper table-responsive">
                                <table border="0" width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Downline</th>
                                        <th>Upline</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $sql = "SELECT * FROM members WHERE ReferalID IS NOT NULL ";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo "" . $row['FirstName'] . ""; ?></td>
                                            <td><?php echo "" . $row['LastName'] . ""; ?></td>
                                            <td><?php echo "" . $row['Email'] . ""; ?></td>
                                            <td><?php echo "" . $row['Phone'] . ""; ?></td>
                                            <td><?php echo "" . $row['Username'] . ""; ?></td>
                                            <td><?php echo "" . $row['ReferalID'] . ""; ?></td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- JS files -->
    <script src="/SecurePrimeInvestment/admin/js/jquery.dataTables.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/js/dataTables.bootstrap.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/js/dataTables.responsive.js"></script>
    <script src="/SecurePrimeInvestment/admin/js/jquery-3.4.1.js"></script>
    <script src="/SecurePrimeInvestment/admin/js/jquery-3.4.1.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/js/flot-data.js"></script>
    <script src="/SecurePrimeInvestment/admin/js/morris-data.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/dist/js/sb-admin-2.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/dist/js/sb-admin-2.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="/SecurePrimeInvestment/admin/dist/js/sb-admin-2.js"></script>
    <script src="Chart.min.js"></script>
    <script src="utils.js"></script>
    <script type="text/javascript">
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