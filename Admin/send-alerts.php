<?php
session_start();
require_once "../includes/connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("location:../auth-signin.php");
} else {
    $user_id = $_SESSION["admin_id"];

    // If a report is submitted
    if (isset($_POST["submit_report"])) {
        $emergency_id = "RPT-" . $user_id . substr(uniqid(), 6, 3);
        $emergency_description = $_POST["emergency_description"];
        $emergency_type = $_POST["emergency_type"];
        $emergency_address = $_POST["emergency_address"];
        $media = $_FILES["media"];
        $real_file_name = $media["name"];
        $real_tmp_name = $media["tmp_name"];

        $file_location = "media/" . $real_file_name;
        if (move_uploaded_file($real_tmp_name, $file_location) || !$real_file_name) {

            // If file is not uploaded
            $file_location = (!$real_file_name) ? null : $file_location;

            // Insert Into DB
            $insert_into_DB = mysqli_query($connect, "INSERT INTO `my_report` (emergency_id,admin_id,emergency_description,emergency_type,location,file) VALUES('$emergency_id',$user_id,'$emergency_description','$emergency_type','$emergency_address','$file_location')");

            if ($insert_into_DB) {
                echo "
                    <script>
                        alert('success');
                        window.location.href='send-alerts.php';
                    </script>
                ";
                die();
            }
        }
    }

    // If a report is deleted
    if (isset($_POST["delete_report"])) {
        $emergency_id = $_POST["emergency_id"];
        $delete_from_DB = mysqli_query($connect, "DELETE FROM `my_report` WHERE emergency_id='$emergency_id'");

        if ($delete_from_DB) {
            echo "
                    <script>
                        alert('success');
                        window.location.href='send-alerts.php';
                    </script>
                ";
            die();
        }
    }

    // If a report status is updated
    if (isset($_POST["update_status"])) {
        $emergency_id = $_POST["emergency_id"];
        $new_status = $_POST["new_status"];
        $update_status_query = mysqli_query($connect, "UPDATE `my_report` SET status='$new_status' WHERE emergency_id='$emergency_id'");

        if ($update_status_query) {
            echo "
                    <script>
                        alert('Status updated successfully');
                        window.location.href='send-alerts.php';
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
        .alert-form-container {
            margin: 20px;
        }

        .alert-form {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .alert-form .form-group {
            margin-bottom: 15px;
        }

        .alert-form .form-control {
            border-radius: 5px;
        }

        .alert-form .btn {
            border-radius: 5px;
            font-weight: bold;
        }

        .priority-select .custom-select {
            border-radius: 5px;
        }

        .date-range-picker {
            border-radius: 5px;
        }

        .alert-list {
            margin: 20px;
        }

        .alert-table {
            width: 100%;
            border-collapse: collapse;
        }

        .alert-table th,
        .alert-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .alert-table th {
            background-color: #f2f2f2;
        }

        .alert-actions .btn {
            margin-right: 5px;
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
                            <span>Admin</span>
                            <div id="more-details"><i class="fa fa-chevron-down m-l-5"></i></div>
                        </div>
                    </div>
                    <div class="collapse" id="nav-user-link">
                        <ul class="list-unstyled">
                            <li class="list-group-item"><a href="auth-normal-sign-in.html"><i class="feather icon-log-out m-r-5"></i>Logout</a></li>
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
            <div class="container mt-4">
                <!-- Send Alert Form -->
                <div class="alert-form-container">
                    <div class="alert-form">
                        <h4>Create New Alert</h4>
                        <form action="./send-alerts.php" method="POST" enctype="multipart/form-data">
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
                                <label for="location">Emergency Address</label>
                                <input type="text" class="form-control" id="location" name="emergency_address" placeholder="Enter Emergency Address manually">
                            </div>
                            <div class="form-group">
                                <label for="media">Upload Image/Video (optional)</label>
                                <input type="file" class="form-control-file" id="media" name="media" accept="image/*, video/*">
                            </div>
                            <button type="submit" name="submit_report" class="btn btn-primary">Send Alert</button>
                        </form>
                    </div>
                </div>

                <!-- Alerts List -->
                <div class="alert-list">
                    <h4>All Alerts</h4>
                    <table class="alert-table table table-striped">
                        <thead>
                            <tr>
                                <th>Report ID</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select_reports = mysqli_query($connect, "SELECT * FROM `my_report` WHERE admin_id = $user_id");

                            while ($row = mysqli_fetch_assoc($select_reports)) {
                            ?>
                                <tr>
                                    <td><?php echo $row["emergency_id"]; ?></td>
                                    <td><?php echo $row["emergency_type"]; ?></td>
                                    <td><?php echo $row["date_created"]; ?></td>
                                    <td class="d-flex justify-content-center">
                                        <button class="btn btn-info view-alert-btn mr-3" data-toggle="modal" data-target="#alert-detail-modal<?php echo $row["emergency_id"]; ?>">View Details</button>
                                        <form action="./send-alerts.php" method="post" class="mr-3">
                                            <input type="hidden" name="emergency_id" value="<?php echo $row["emergency_id"]; ?>">
                                            <button type="submit" name="delete_report" class="btn btn-danger delete-alert-btn">Delete</button>
                                        </form>
                                        <!-- Trigger Button for Viewing Details -->
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewDetailsModal<?php echo $row["emergency_id"]; ?>">
                                            Change Status
                                        </button>

                                        <!-- View Details Modal -->
                                        <div class="modal fade" id="viewDetailsModal<?php echo $row["emergency_id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewDetailsModalLabel">Emergency Report Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Display Emergency Details -->
                                                        <p><?php echo $row["emergency_description"]; ?></p>
                                                        <!-- Status Update Form -->
                                                        <form action="./send-alerts.php" method="POST">
                                                            <input type="hidden" name="emergency_id" value="<?php echo $row["emergency_id"]; ?>">
                                                            <div class="form-group">
                                                                <label for="new-status">Update Status</label>
                                                                <select name="new_status" class="form-control">
                                                                    <option value="pending" <?php if ($row["status"] == "pending") echo "selected"; ?>>Pending</option>
                                                                    <option value="in-progress" <?php if ($row["status"] == "in-progress") echo "selected"; ?>>In Progress</option>
                                                                    <option value="resolved" <?php if ($row["status"] == "resolved") echo "selected"; ?>>Resolved</option>
                                                                </select>
                                                            </div>
                                                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                    </td>
                                </tr>

                                <!-- View Alert Details Modal -->
                                <div class="modal fade" id="alert-detail-modal<?php echo $row["emergency_id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="alertDetailModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="alertDetailModalLabel">Alert Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" id="alert-id" value="">
                                                <h6>Alert Information</h6>
                                                <p>
                                                    <strong>Type:</strong>
                                                    <span><?php echo $row["emergency_type"]; ?></span>
                                                </p>
                                                <p>
                                                    <strong>Message:</strong>
                                                    <span>
                                                        <?php echo $row["emergency_description"]; ?>
                                                    </span>
                                                </p>
                                                <p>
                                                    <strong>Location:</strong>
                                                    <span>
                                                        <?php echo $row["location"]; ?>
                                                    </span>
                                                </p>
                                                <p>
                                                    <strong>Status:</strong>
                                                    <span>
                                                        <?php echo $row["status"]; ?>
                                                    </span>
                                                </p>
                                                <p>
                                                    <strong>Date:</strong>
                                                    <span>
                                                        <?php echo $row["date_created"]; ?>
                                                    </span>
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

                                <!-- Detailed Report View Modal -->
                                <div class="modal fade" id="report-detail-modal" tabindex="-1" role="dialog" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="reportDetailModalLabel">Report Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="emergency-id" value="">

                                                <h6>Update Status</h6>
                                                <select id="report-status-update" class="form-control">
                                                    <option value="pending">Pending</option>
                                                    <option value="in-progress">In Progress</option>
                                                    <option value="resolved">Resolved</option>
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-success" id="update-status">Update Status</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            <?php } ?>
                        </tbody>
                    </table>
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