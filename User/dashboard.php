<?php
session_start();
require_once "../includes/connect.php";

if (!isset($_SESSION["user_id"])) {
    header("location:../auth-signin.php");
} else {
    $user_id = $_SESSION["user_id"];

    // Select User Details
    $query = "SELECT * FROM `user` WHERE user_id = $user_id";
    $select_user_details = mysqli_query($connect, $query);
    if ($select_user_details && mysqli_num_rows($select_user_details) > 0) {
        $fetch_user_details = mysqli_fetch_assoc($select_user_details);

        $fullname = $fetch_user_details["fullname"];
        $email = $fetch_user_details["email"];
        $password = $fetch_user_details["password"];
    } else {
        // Handle case where user details are not found
        echo "User details not found.";
        exit();
    }
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

    <style>
        /* Custom styles for cards */
        .card-header {
            font-weight: bold;
            font-size: 1.2em;
        }

        .list-group-item {
            border: none;
            padding: 0.75rem 1.25rem;
        }

        .badge {
            font-size: 0.9em;
            padding: 0.5em;
        }

        .card-body p {
            font-size: 1.1em;
        }

        .card-body .btn {
            font-size: 1em;
        }
    </style>

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
                            <span>User</span>
                            <div id="more-details"><?php echo $fullname ?><i class="fa fa-chevron-down m-l-5"></i></div>
                        </div>
                    </div>
                    <div class="collapse" id="nav-user-link">
                        <ul class="list-unstyled">
                            <li class="list-group-item"><a href="profile.php"><i class="feather icon-user m-r-5"></i>View Profile</a></li>

                            <li class="list-group-item"><a href="./logout.php"><i class="feather icon-log-out m-r-5"></i>Logout</a></li>
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
                        <a href="my-reports.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">My reports</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="alerts.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Alerts</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="profile.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Profile</span></a>
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
    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="container-fluid">
                <div class="col-md-6 col-lg-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="text-danger">Note!</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                            </ul>
                            <div class="mt-3">
                                <p>Quickly click to see all emergency Situations on the Alert tab</p>
                                <a href="alerts.php" class="btn btn-outline-danger">Alerts</a>
                            </div>


                            <div class="card mt-3">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Report an Emergency</h5>
                                    <p class="card-text">Click the button below to report an emergency immediately.</p>
                                    <a href="my-reports.php" class="btn btn-danger btn-lg">
                                        Report Now <i class="feather icon-send"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">


                </div>
                <!-- End of Dashboard Cards -->
            </div>
        </div>
    </div> <!-- Required Js -->
    <script src="../assets/js/vendor-all.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/pcoded.min.js"></script>

    <!-- Apex Chart -->
    <script src="../assets/js/plugins/apexcharts.min.js"></script>


    <!-- custom-chart js -->
    <script src="../assets/js/pages/dashboard-main.js"></script>
</body>

</html>