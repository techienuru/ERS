<?php
session_start();
require_once "../includes/connect.php";

if (!isset($_SESSION["user_id"])) {
    header("location:../auth-signin.php");
    exit();
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

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_fullname = mysqli_real_escape_string($connect, $_POST['fullname']);
    $new_email = mysqli_real_escape_string($connect, $_POST['email']);
    $new_password = mysqli_real_escape_string($connect, $_POST['password']);

    // Update the user details
    $update_query = "UPDATE `user` SET fullname = '$new_fullname', email = '$new_email', password = '$new_password' WHERE user_id = $user_id";
    if (mysqli_query($connect, $update_query)) {

        echo '
            <script>
                alert("Profile update Successful");
                window.location.href="./profile.php";
            </script>
        ';
    } else {
        echo "Error updating profile: " . mysqli_error($connect);
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Emergency Reporting System</title>
    <!-- Meta and Favicon -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Phoenixcoded" />
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .profile-form {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-form h4 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .profile-form .form-group label {
            font-weight: bold;
        }

        .update-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .update-btn:hover {
            background-color: #0056b3;
        }

        .modal-body .form-control {
            margin-bottom: 15px;
        }

        .save-btn {
            background-color: #28a745;
            color: white;
            transition: background-color 0.3s;
        }

        .save-btn:hover {
            background-color: #218838;
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



    <!-- Main Content -->
    <div class="pcoded-main-container">
        <div class="pcoded-content">
            <div class="container">
                <div class="profile-form">
                    <h4>Profile Settings</h4>
                    <form>
                        <div class="form-group">
                            <label for="fullName">Full Name</label>
                            <input type="text" class="form-control" id="fullName" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" value="<?php echo htmlspecialchars($password); ?>" readonly>
                        </div>
                        <button type="button" class="update-btn" data-toggle="modal" data-target="#editProfileModal">
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>

            <!-- Edit Profile Modal -->
            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editProfileForm" method="POST">
                                <div class="form-group">
                                    <label for="fullname">Full Name</label>
                                    <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter new password" required>
                                </div>
                                <button type="submit" class="btn save-btn" name="update_profile">Save Changes</button>
                            </form>
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