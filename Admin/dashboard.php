<?php
session_start();
require_once "../includes/connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("location:../auth-signin.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Emergency Reporting System</title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!-- Favicon icon -->
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">

    <!-- vendor css -->
    <link rel="stylesheet" href="../assets/css/style.css">



</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar  ">
        <div class="navbar-wrapper  ">
            <div class="navbar-content scroll-div ">

                <div class="">
                    <div class="main-menu-header">
                        <div class="user-details">
                            <span>Admin</span>
                            <div id="more-details"><i class="fa fa-chevron-down m-l-5"></i></div>
                        </div>
                    </div>
                    <div class="collapse" id="nav-user-link">
                        <ul class="list-unstyled">
                            <li class="list-group-item"><a href="logout.php"><i class="feather icon-log-out m-r-5"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>

                <ul class="nav pcoded-inner-navbar ">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="manage-reports.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Manage reports</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="send-alerts.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Send Alerts</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="user-management.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Manage users</span></a>
                    </li>




                </ul>


            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->
    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light header-dark">


        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            <a href="#!" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <h3 class="text-danger">ERS</h3>
                <img src="../assets/images/logo-icon.png" alt="" class="logo-thumb">
            </a>
            <a href="#!" class="mob-toggler">
                <i class="feather icon-more-vertical"></i>
            </a>
        </div>



    </header>
    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="row">
                <?php
                $select_no_of_report = mysqli_query($connect, "SELECT COUNT(emergency_id) AS noOfReport FROM `my_report`");
                $fetch_no_of_report = mysqli_fetch_assoc($select_no_of_report);


                $select_no_of_users = mysqli_query($connect, "SELECT COUNT(user_id) AS noOfUsers FROM `user`");
                $fetch_no_of_users = mysqli_fetch_assoc($select_no_of_users);

                $select_no_of_alerts = mysqli_query($connect, "SELECT COUNT(emergency_id) AS noOfAlerts FROM `my_report` WHERE admin_id IS NOT NULL");
                $fetch_no_of_alerts = mysqli_fetch_assoc($select_no_of_alerts);
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card summary-card">
                        <div class="card-body">
                            <h5>Total Reports</h5>
                            <h2 id="total-reports"><?php echo $fetch_no_of_report["noOfReport"]; ?></h2>
                            <div class="trend"><i class="feather icon-trending-up"></i> +5% <span>Last Week</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card summary-card">
                        <div class="card-body">
                            <h5>Alerts Sent</h5>
                            <h2 id="alerts-sent">
                                <?php echo $fetch_no_of_alerts["noOfAlerts"]; ?>
                            </h2>
                            <div class="trend"><i class="feather icon-trending-down"></i> -3% <span>Last Week</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card summary-card">
                        <div class="card-body">
                            <h5>Total registered Users</h5>
                            <h2 id="users-online">
                                <?php echo $fetch_no_of_users["noOfUsers"]; ?>
                            </h2>
                            <div class="trend"><i class="feather icon-trending-up"></i> +10% <span>Last Week</span></div>
                        </div>
                    </div>
                </div>
                <!-- Quick Access Panels -->
                <div class="col-lg-6 col-md-12">
                    <div class="card recent-reports">
                        <div class="card-body">
                            <h5>Latest Reports</h5>
                            <ul id="reports-list">
                                <!-- Dynamic list items go here -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card recent-alerts">
                        <div class="card-body">
                            <h5>Recent Alerts</h5>
                            <ul id="alerts-list">
                                <!-- Dynamic list items go here -->
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Charts -->
            </div>
        </div>
    </div>


    <!-- Required Js -->
    <script src="../assets/js/vendor-all.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/pcoded.min.js"></script>

    <!-- Apex Chart -->
    <script src="../assets/js/plugins/apexcharts.min.js"></script>


    <!-- custom-chart js -->
    <script src="../assets/js/pages/dashboard-main.js"></script>


</body>

</html>