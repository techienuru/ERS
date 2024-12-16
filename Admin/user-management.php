<?php
session_start();
require_once "../includes/connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("location:../auth-signin.php");
} else {
    // If account is deactivated
    if (isset($_POST["deactivate_acc"]) || isset($_POST["activate_acc"])) {
        $user_id = $_POST["user_id"];

        if (isset($_POST["deactivate_acc"])) {
            $update_DB = mysqli_query($connect, "UPDATE `user` SET status = 0 WHERE user_id=$user_id");
        } elseif (isset($_POST["activate_acc"])) {
            $update_DB = mysqli_query($connect, "UPDATE `user` SET status = 1 WHERE user_id=$user_id");
        }

        if ($update_DB) {
            echo "
                    <script>
                        alert('success');
                        window.location.href='user-management.php';
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
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <!-- Favicon icon -->
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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
    <nav class="pcoded-navbar">
        <div class="navbar-wrapper">
            <div class="navbar-content scroll-div">
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
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigation</label>
                    </li>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="manage-reports.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Manage reports</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="send-alerts.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Send Alerts</span></a>
                    </li>
                    <li class="nav-item">
                        <a href="user-management.php" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Manage users</span></a>
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
                <h2>User Management</h2>
                <div class="row my-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="searchUser" placeholder="Search by username or email">
                    </div>
                </div>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col">Role</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $select_reports = mysqli_query($connect, "SELECT * FROM `user`");

                        while ($row = mysqli_fetch_assoc($select_reports)) {
                            // Checks status and update the status badge
                            $status_badge = ($row["status"] == 1) ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>";

                            // Checks status and update the Action button
                            $change_status_button = ($row["status"] == 1) ? "<button type='submit' name='deactivate_acc' class='btn btn-danger btn-sm deactivate-user-btn'>Deactivate</button>" : "<button type='submit' name='activate_acc' class='btn btn-success btn-sm deactivate-user-btn'>Activate</button>";
                        ?>
                            <tr>
                                <td><?php echo $row["fullname"]; ?></td>
                                <td><?php echo $row["email"]; ?></td>
                                <td><?php echo $status_badge; ?></td>
                                <td>User</td>
                                <td>
                                    <form action="./user-management.php" method="post">
                                        <input type="hidden" name="user_id" value="<?php echo $row["user_id"]; ?>">
                                        <?php echo $change_status_button; ?>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Modal content will go here -->
                    <p>User details and forms will be dynamically loaded here based on the action.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/js/vendor-all.min.js"></script>
    <script src="../assets/js/plugins/bootstrap.min.js"></script>
    <script src="../assets/js/pcoded.min.js"></script>

    <!-- Apex Chart -->
    <script src="../assets/js/plugins/apexcharts.min.js"></script>

    <!-- custom-chart js -->
    <script src="../assets/js/pages/dashboard-main.js"></script>

</body>

</html>