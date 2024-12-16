<?php
session_start();
require_once "../includes/connect.php";

// Make sure this is the only thing that executes if not logged in
if (!isset($_SESSION["admin_id"])) {
    header("location:../auth-signin.php");
    exit(); // Ensure no further code runs if session is not valid
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure data exists before processing
    if (isset($_POST['emergency_id'])) {
        $emergency_id = $_POST['emergency_id'];

        if (isset($_POST['update_status'])) {
            // Update the status
            $status = $_POST['status'];
            $query = "UPDATE `my_report` SET `status` = ? WHERE `emergency_id` = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param('ss', $status, $emergency_id);
            if ($stmt->execute()) {
                echo 'Status updated successfully';
            } else {
                echo 'Failed to update status';
            }
        } elseif (isset($_POST['send_response'])) {
            // Send the response
            $response_message = $_POST['response_message'];
            $query = "UPDATE `my_report` SET `admin_response` = ? WHERE `emergency_id` = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param('ss', $response_message, $emergency_id);
            if ($stmt->execute()) {
                echo 'Response sent successfully';
            } else {
                echo 'Failed to send response';
            }
        } elseif (isset($_POST["delete_report"])) {
            // Delete the report
            $query = "DELETE FROM `my_report` WHERE `emergency_id` = ?";
            $stmt = $connect->prepare($query);
            $stmt->bind_param('s', $emergency_id);
            if ($stmt->execute()) {
                echo '
                    <script>
                        alert("successful!");   
                        window.location.href="manage-reports.php";
                    </script>
                ';
            } else {
                echo '
                    <script>
                        alert("Failed to delete report!");   
                        window.location.href="manage-reports.php";
                    </script>
                ';
            }
        }
    } else {
        echo 'Missing emergency_id';
    }



    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Emergency Reporting System</title>
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
                <!-- Filters Panel -->
                <form id="filters-form" class="form-inline">
                    <div class="form-group">
                        <label for="report-type">Type:</label>
                        <select id="report-type" class="form-control mx-2">
                            <option value="">All Types</option>
                            <option value="health">Health</option>
                            <option value="crime">Crime</option>
                            <option value="fire">Fire</option>
                        </select>
                    </div>
                    <div class="form-group mx-2">
                        <label for="report-status">Status:</label>
                        <select id="report-status" class="form-control mx-2">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>

                    <div class="form-group mx-2">
                        <label for="search">Search:</label>
                        <input type="text" id="search" class="form-control mx-2" placeholder="Search by Report ID">
                    </div>
                    <button type="button" id="apply-filters" class="btn btn-primary">Apply Filters</button>
                </form>



                <!-- Report List -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="reports-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Report ID</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select_reports = mysqli_query($connect, "SELECT * FROM `my_report` INNER JOIN `user` ON my_report.user_id = user.user_id WHERE admin_id IS NULL");

                                    while ($row = mysqli_fetch_array($select_reports)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row["emergency_id"]; ?></td>
                                            <td><?php echo $row["emergency_type"]; ?></td>
                                            <td><?php echo $row["8"]; ?></td>
                                            <td><?php echo $row["date_created"]; ?></td>
                                            <td class="d-flex">
                                                <button class="btn btn-info btn-sm view-details-btn mr-3" data-toggle="modal" data-target="#report-detail-modal<?php echo $row["emergency_id"]; ?>">View Details</button>
                                                <form action="./manage-reports.php" method="post">
                                                    <input type="hidden" name="emergency_id" value="<?php echo $row["emergency_id"]; ?>">
                                                    <button type="submit" name="delete_report" class="btn btn-danger btn-sm delete-alert-btn">Delete</button>
                                                </form>
                                            </td>
                                        </tr>

                                        <!-- Detailed Report View Modal -->
                                        <div class="modal fade" id="report-detail-modal<?php echo $row["emergency_id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="reportDetailModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="reportDetailModalLabel">Report Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" id="modal-emergency-id" value="<?php echo $row["emergency_id"]; ?>">
                                                        <h6 class="text-danger">User Information</h6>
                                                        <p>
                                                            <strong>Name:</strong>
                                                            <span><?php echo $row["fullname"]; ?></span>
                                                        </p>
                                                        <p>
                                                            <strong>Contact:</strong>
                                                            <span><?php echo $row["mobile_no"]; ?></span>
                                                        </p>
                                                        <p>
                                                            <strong>Location:</strong>
                                                            <span><?php echo $row["location"]; ?></span>
                                                        </p>

                                                        <h6 class="text-danger">Report Information</h6>
                                                        <p>
                                                            <strong>Description:</strong>
                                                            <span><?php echo $row["emergency_description"]; ?></span>
                                                        </p>

                                                        <!-- Media Display -->
                                                        <?php if (!empty($row["file"])): ?>
                                                            <div class="media-container" style="margin-top: 20px; text-align: center;">
                                                                <?php
                                                                $mediaPath = "../User/" . $row["file"];
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

                                                        <h6>Update Status</h6>
                                                        <select id="report-status-update<?php echo $row["emergency_id"]; ?>" class="form-control" data-id="<?php echo $row["emergency_id"]; ?>">
                                                            <option value="pending" <?php if ($row["status"] === 'pending') echo 'selected'; ?>>Pending</option>
                                                            <option value="in-progress" <?php if ($row["status"] === 'in-progress') echo 'selected'; ?>>In Progress</option>
                                                            <option value="resolved" <?php if ($row["status"] === 'resolved') echo 'selected'; ?>>Resolved</option>
                                                        </select>
                                                        <button type="button" class="btn mt-2 btn-success update-status" data-id="<?php echo $row["emergency_id"]; ?>">Update Status</button>

                                                        <h6>Response</h6>
                                                        <textarea id="response-message<?php echo $row["emergency_id"]; ?>" class="form-control" rows="3" placeholder="Type your response here"></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary send-response" data-id="<?php echo $row["emergency_id"]; ?>">Send Response</button>
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
        </div>
    </div>


    <!-- Required Js -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/js/vendor-all.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/pcoded.min.js"></script>

    <!-- Apex Chart -->
    <script src="../assets/js/plugins/apexcharts.min.js"></script>


    <!-- custom-chart js -->
    <script src="../assets/js/pages/dashboard-main.js"></script>

    <script>
        $(document).ready(function() {
            // Handle Update Status
            $('.update-status').click(function() {
                var emergencyId = $(this).data('id');
                var status = $('#report-status-update' + emergencyId).val();

                $.ajax({
                    url: 'manage-reports.php',
                    type: 'POST',
                    data: {
                        update_status: true,
                        emergency_id: emergencyId,
                        status: status
                    },
                    success: function(response) {
                        alert(response); // Show the success message
                        location.reload(); // Refresh the page or update the table as needed
                    },
                    error: function() {
                        alert('Error updating status');
                    }
                });
            });

            // Handle Send Response
            $('.send-response').click(function() {
                var emergencyId = $(this).data('id');
                var responseMessage = $('#response-message' + emergencyId).val();

                $.ajax({
                    url: 'manage-reports.php',
                    type: 'POST',
                    data: {
                        send_response: true,
                        emergency_id: emergencyId,
                        response_message: responseMessage
                    },
                    success: function(response) {
                        alert(response); // Show the success message
                        $('#response-message' + emergencyId).val(''); // Clear the textarea
                    },
                    error: function() {
                        alert('Error sending response');
                    }
                });
            });
        });



        // filter 
        document.getElementById('apply-filters').addEventListener('click', function() {
            const type = document.getElementById('report-type').value;
            const status = document.getElementById('report-status').value;

            const search = document.getElementById('search').value;

            // Create the query string for filters
            const queryString = `type=${type}&status=${status}&search=${search}`;

            // Fetch the filtered reports
            fetch(`./filter-reports.php?${queryString}`)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('reports-table').innerHTML = data;
                });
        });
    </script>


</body>

</html>