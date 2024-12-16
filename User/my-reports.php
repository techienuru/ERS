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

    // If a report is submitted
    if (isset($_POST["submit_report"])) {
        $emergency_id = "RPT-" . $user_id . substr(uniqid(), 6, 3);
        $emergency_description = $_POST["emergency_description"];
        $emergency_type = $_POST["emergency_type"];
        $mobile_number = $_POST["mobile_number"];
        $emergency_address = $_POST["emergency_address"];
        $media = $_FILES["media"];
        $real_file_name = $media["name"];
        $real_tmp_name = $media["tmp_name"];

        $file_location = "media/" . $real_file_name;
        if (move_uploaded_file($real_tmp_name, $file_location) || !$real_file_name) {

            // If file is not uploaded
            $file_location = (!$real_file_name) ? null : $file_location;

            // Insert Into DB
            $insert_into_DB = mysqli_query($connect, "INSERT INTO `my_report` (emergency_id,user_id,emergency_description,emergency_type,mobile_no,location,file) VALUES('$emergency_id',$user_id,'$emergency_description','$emergency_type','$mobile_number','$emergency_address','$file_location')");

            if ($insert_into_DB) {
                echo "
                    <script>
                        alert('success');
                        window.location.href='my-reports.php';
                    </script>
                ";
                die();
            }
        }
    }

    // if a report is deleted
    if (isset($_POST["delete_report"])) {
        $emergency_id = $_POST["emergency_id"];
        $delete_from_DB = mysqli_query($connect, "DELETE FROM `my_report` WHERE emergency_id='$emergency_id'");

        if ($delete_from_DB) {
            echo "
                    <script>
                        alert('success');
                        window.location.href='my-reports.php';
                    </script>
                ";
            die();
        }
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
            <div class="row">
                <!-- Report an Emergency Form start -->
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Report an Emergency</h5>
                        </div>
                        <div class="card-body">
                            <form action="./my-reports.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="description">Emergency Description</label>
                                    <textarea class="form-control" id="description" name="emergency_description" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="emergencyType">Emergency Type</label>
                                    <select class="form-control" id="emergencyType" name="emergency_type" required>
                                        <option value="">Select Type</option>
                                        <option value="health">Health</option>
                                        <option value="crime">Crime</option>
                                        <option value="fire">Fire</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="location">Mobile No.</label>
                                    <input type="text" class="form-control" id="location" name="mobile_number" placeholder="Input a valid Mobile no please">
                                </div>

                                <div class="form-group">
                                    <label for="location">Emergency Address</label>
                                    <input type="text" class="form-control" id="location" name="emergency_address" placeholder="Enter Emergency Address manually">
                                </div>
                                <div class="form-group">
                                    <label for="media">Upload Image/Video (optional)</label>
                                    <input type="file" class="form-control-file" id="media" name="media" accept="image/*, video/*">
                                </div>
                                <button type="submit" name="submit_report" class="btn btn-primary">Submit Report</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Report an Emergency Form end -->

                <!-- My Reports section -->
                <div class="col-lg-12 col-md-12 mt-4">
                    <div class="card table-card">
                        <div class="card-header">
                            <h5>My Reports</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Report ID</th>
                                            <th>Type</th>
                                            <th>Timestamp</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportsTableBody">
                                        <?php
                                        $select_reports = mysqli_query($connect, "SELECT * FROM `my_report` WHERE user_id = $user_id");

                                        while ($row = mysqli_fetch_assoc($select_reports)) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row["emergency_id"]; ?></td>
                                                <td><?php echo $row["emergency_type"]; ?></td>
                                                <td><?php echo $row["date_created"]; ?></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <?php echo $row["status"]; ?>
                                                    </span>
                                                </td>
                                                <td class="d-flex">
                                                    <button class="btn btn-sm btn-primary mr-3" data-toggle="modal" data-target="#reportDetailModal<?php echo $row["emergency_id"]; ?>">View Details</button>

                                                    <form action="./my-reports.php" method="post">
                                                        <input type="hidden" name="emergency_id" value="<?php echo $row["emergency_id"]; ?>">
                                                        <button class="btn btn-sm btn-danger" name="delete_report">Delete</button>
                                                    </form>
                                                </td>



                                                <!-- Report Detail Modal -->
                                                <div class="modal fade" id="reportDetailModal<?php echo $row["emergency_id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="reportDetailModalLabel">Report Details</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Report details content goes here -->
                                                                <p>
                                                                    <strong>Report ID:</strong>
                                                                    <?php echo $row["emergency_id"]; ?>
                                                                </p>
                                                                <p>
                                                                    <strong>Title:</strong> <?php echo $row["emergency_type"]; ?>
                                                                </p>
                                                                <p>
                                                                    <strong>Description:</strong>
                                                                    <?php echo $row["emergency_description"]; ?>
                                                                </p>
                                                                <p>
                                                                    <strong>Status:</strong> <?php echo $row["status"]; ?>
                                                                </p>
                                                                <p>
                                                                    <strong>Submitted At:</strong>
                                                                    <?php echo $row["date_created"]; ?>
                                                                </p>
                                                                <p>
                                                                    <strong>Admin Response:</strong>
                                                                    <?php echo $row["admin_response"]; ?>
                                                                </p>
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
                                                <!-- End Report Detail Modal -->
                                            <?php } ?>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
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