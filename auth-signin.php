<?php
session_start();
include_once "includes/connect.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$email = $_POST["email"];
	$password = $_POST["password"];

	$selecting_admin = mysqli_query($connect, "SELECT * FROM `admin` WHERE email = '$email' AND password = '$password'");

	$selecting_user = mysqli_query($connect, "SELECT * FROM `user` WHERE email = '$email' AND password = '$password' AND status = 1");


	if (!mysqli_num_rows($selecting_admin) > 0 && !mysqli_num_rows($selecting_user) > 0) {
		$error_message = '
            <div class="alert alert-danger position-absolute js-invalid-credential" style="z-index: 1; top:0;right:0;">
                Invalid Credential
            </div>
            ';

		echo '
        <script>
            setInterval(()=>{
                document.querySelector(".js-invalid-credential").style.display = "none";
            },3000);
        </script>
    ';
	} else {
		if (mysqli_num_rows($selecting_admin) > 0) {
			$row = mysqli_fetch_assoc($selecting_admin);
			$_SESSION["admin_id"] = $row["admin_id"];
			header("location:./admin/dashboard.php");
			die();
		} elseif (mysqli_num_rows($selecting_user) > 0) {
			$row = mysqli_fetch_assoc($selecting_user);
			$_SESSION["user_id"] = $row["user_id"];
			header("location:./user/dashboard.php");
			die();
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

	<title>Emergency Response System</title>
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
	<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

	<!-- vendor css -->
	<link rel="stylesheet" href="assets/css/style.css">




</head>

<!-- [ auth-signin ] start -->
<div class="auth-wrapper">
	<div class="auth-content text-center">
		<?php
		if (isset($error_message)) {
			echo $error_message;
		}
		?>
		<h3 class="text-white">E.R.S</h3>
		<div class="card borderless">
			<div class="row align-items-center ">
				<div class="col-md-12">
					<div class="card-body">
						<form action="auth-signin.php" method="POST">
							<h4 class="mb-3 f-w-400">Signin</h4>
							<hr>
							<div class="form-group mb-3">
								<input type="email" class="form-control" id="Email" name="email" placeholder="Email address">
							</div>
							<div class="form-group mb-4">
								<input type="password" class="form-control" id="Password" name="password" placeholder="Password">
							</div>

							<button class="btn btn-block btn-primary mb-4" name="submit">Signin</button>
							<hr>
							<p class="mb-0 text-muted">Don’t have an account? <a href="auth-signup.php" class="f-w-400">Signup</a></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- [ auth-signin ] end -->

<!-- Required Js -->
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>

<script src="assets/js/pcoded.min.js"></script>



</body>

</html>