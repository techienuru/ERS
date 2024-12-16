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
        .alert-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .alert-card:hover {
            transform: scale(1.02);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .badge-critical {
            background-color: red;
        }

        .badge-warning {
            background-color: yellow;
            color: black;
        }

        .badge-info {
            background-color: blue;
        }

        .badge-resolved {
            background-color: green;
        }

        /* Animation for the media content */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .media-container img,
        .media-container video {
            animation: fadeIn 1s ease-in-out;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
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
                            <div id="more-details"><?php echo $fullname; ?><i class="fa fa-chevron-down m-l-5"></i></div>
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
    <!-- [ Header ] end -->



    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Emergency Alerts</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $select_reports = mysqli_query($connect, "SELECT * FROM `my_report`");

                                while ($row = mysqli_fetch_assoc($select_reports)) {
                                ?>
                                    <div class="alert-card card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $row["emergency_type"]; ?></h5>
                                            <p class="card-text"><span class="badge badge-critical text-white">Alert</span> | <span>Location: <?php echo $row["location"]; ?></span> | <span>Alert Time: <?php echo $row["date_created"]; ?></span></p>
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#alertDetailsModal<?php echo $row["emergency_id"]; ?>">View Details</button>
                                        </div>
                                    </div>

                                    <!-- Alert Details Modal -->
                                    <div class="modal fade" id="alertDetailsModal<?php echo $row["emergency_id"]; ?>" tabindex="-1" aria-labelledby="alertDetailsModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="alertDetailsModalLabel">Alert Details</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h6 class="text-warning">
                                                        Description:
                                                        <span class="text-dark">
                                                            <?php echo $row["emergency_description"]; ?>
                                                        </span>
                                                    </h6>
                                                    <h6>
                                                        <strong class="text-primary">Admin Response:</strong>
                                                        <p>
                                                            <?php echo $row["admin_response"]; ?>
                                                        </p>
                                                    </h6>
                                                </div>
                                                <!-- Media Display -->
                                                <?php if (!empty($row["file"])): ?>
                                                    <div class="media-container" style="margin-top: 20px; text-align: center;">
                                                        <?php
                                                        $mediaPath = $row["file"];
                                                        $mediaType = mime_content_type($mediaPath);
                                                        if (strpos($mediaType, 'image') !== false) {
                                                            echo '<img src="' . $mediaPath . '" alt="Emergency Media" class="img-fluid" style="max-width: 100%; border-radius: 10px; animation: fadeIn 1s ease-in-out;">';
                                                        } elseif (strpos($mediaType, 'video') !== false) {
                                                            echo '<video controls class="img-fluid" style="max-width: 100%; border-radius: 10px; animation: fadeIn 1s ease-in-out;">
                                          <source src="' . $mediaPath . '" type="' . $mediaType . '">
                                          Your browser does not support the video tag.
                                      </video>';
                                                        }
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
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