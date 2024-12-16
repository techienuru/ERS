<?php
include("includes/connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$fullname = $_POST["fullname"];
	$email = $_POST["email"];
	$password = $_POST["password"];

	$sql = mysqli_query($connect, "INSERT INTO `user` (fullname,email,password) VALUES('$fullname','$email','$password')");

	if ($sql) {
		echo '
            <script>
                alert("Account creation Successful");
                window.location.href="./auth-signin.php";
            </script>
        ';
		die();
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
	<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

	<!-- vendor css -->
	<link rel="stylesheet" href="assets/css/style.css">


</head>

<!-- [ auth-signup ] start -->
<div class="auth-wrapper">
	<div class="auth-content text-center">
		<h3 class="text-white">E.R.S</h3>
		<div class="card borderless">
			<div class="row align-items-center text-center">
				<div class="col-md-12">
					<div class="card-body">
						<h4 class="f-w-400">Sign up</h4>
						<hr>
						<form action="auth-signup.php" method="POST">
							<div class="form-group mb-3">
								<input type="text" class="form-control" id="Username" name="fullname" placeholder="Full name" required>
							</div>
							<div class="form-group mb-3">
								<input type="email" class="form-control" id="Email" name="email" placeholder="Email address" required>
							</div>
							<div class="form-group mb-4">
								<input type="password" class="form-control" id="Password" name="password" placeholder="Password" required>
							</div>
							<button type="submit" class="btn btn-primary btn-block mb-4" name="submit">Sign up</button>
							<hr>
							<p class="mb-2">Already have an account? <a href="auth-signin.php" class="f-w-400">Signin</a></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- [ auth-signup ] end -->

<!-- Required Js -->
<script src="assets/js/vendor-all.min.js"></script>
<script src="assets/js/plugins/bootstrap.min.js"></script>

<script src="assets/js/pcoded.min.js"></script>



</body>

</html>