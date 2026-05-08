<?php
session_start();
include('includes/config.php');
if(isset($_POST['login']))
{
    $username=$_POST['username'];
    $password=md5($_POST['password']);
    $sql ="SELECT UserName,Password FROM tbladmin WHERE UserName=:username and Password=:password";
    $query= $dbh -> prepare($sql);
    $query-> bindParam(':username', $username, PDO::PARAM_STR);
    $query-> bindParam(':password', $password, PDO::PARAM_STR);
    $query-> execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
        $_SESSION['alogin']=$_POST['username'];
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } 
    else{
        echo "<script>alert('Invalid Details');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RaktNest | Admin Login</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

	<style>
		body {
			margin: 0;
			padding: 0;
			min-height: 100vh;
			background: url('IMG_7459.PNG') no-repeat center center/cover;
			display: flex;
			align-items: center;
			justify-content: flex-end; /* 👈 moved card to right */
			font-family: 'Poppins', sans-serif;
			overflow: hidden;
			position: relative;
			padding-right: 950px; /* spacing from right edge */
		}

		.login-card {
			position: relative;
			z-index: 1;
			width: 480px;
			padding: 60px 50px;
			border-radius: 15px;
			background: rgba(255, 255, 255, 0.15);
			backdrop-filter: blur(15px);
			-webkit-backdrop-filter: blur(15px);
			border: 1px solid rgba(255, 255, 255, 0.25);
			box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
			color: #fff;
			text-align: center;
			animation: fadeIn 1s ease-in-out;
		}

		@keyframes fadeIn {
			from {opacity: 0; transform: translateY(-20px);}
			to {opacity: 1; transform: translateY(0);}
		}

		h2 {
			text-align: center;
			margin-bottom: 25px;
			font-weight: 700;
			color: #ffdf5d;
			text-shadow: 2px 2px 5px rgba(0,0,0,0.8);
		}

		label {
			font-weight: bold;
			color: #ffffff;
			display: block;
			text-align: left;
			margin-top: 10px;
			text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
		}

		.form-control {
			border-radius: 10px;
			border: 1px solid rgba(255,255,255,0.3);
			padding: 10px 15px;
			background: rgba(255,255,255,0.1);
			color: #fff;
			font-size: 15px;
			outline: none;
			transition: 0.3s;
		}

		.form-control::placeholder {
			color: rgba(255, 255, 255, 0.8);
		}

		.form-control:focus {
			border-color: #00e0ff;
			box-shadow: 0 0 10px rgba(0, 200, 255, 0.6);
		}

		.btn-primary {
			background: linear-gradient(45deg, #00c6ff, #0072ff);
			border: none;
			padding: 12px 20px;
			margin-top: 20px;
			width: 100%;
			font-size: 18px;
			font-weight: bold;
			border-radius: 10px;
			cursor: pointer;
			box-shadow: 0 5px 15px rgba(0, 114, 255, 0.4);
			transition: 0.3s;
		}

		.btn-primary:hover {
			background: linear-gradient(45deg, #00ff87, #60efff);
			transform: scale(1.05);
			box-shadow: 0 8px 20px rgba(0, 200, 255, 0.5);
		}

		.btn-outline-light:hover {
			color: black !important;
			background-color: #5effceff !important;
		}

		/* 🔴 Forgot Password link red */
		.text-center a[href="forgot-password.php"] {
			color: red !important;
			font-weight: 600;
			text-decoration: none;
		}

		.text-center a[href="forgot-password.php"]:hover {
			color: red !important;
			text-decoration: underline;
		}

		/* Rest links same as before */
		.text-center a {
			color: #ffdf5d;
			text-decoration: none;
			font-weight: 600;
		}

		.text-center a:hover {
			color: #fff;
			text-decoration: underline;
		}

		.footer {
			margin-top: 20px;
			font-size: 14px;
			color: #58f3a8ff;
			text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
		}
		.btn-outline-light.w-100.mt-2 {
	color: red !important;
	border-color: red !important;
}
.btn-outline-light.w-100.mt-2:hover {
	background-color: red !important;
	color: white !important;
}


		@media (max-width: 768px) {
			body {
				justify-content: center;
				padding-right: 0;
			}
			.login-card {
				width: 90%;
				padding: 30px;
			}
		}
	</style>
</head>

<body>
	<div class="login-card">
		<h2 style="color: #f83d3dff;"><i class="fa-solid fa-user-shield"></i> Admin Login</h2>

		<form method="post">
			<label for="username">Username</label>
			<input type="text" name="username" class="form-control" placeholder="Enter your username" required>

			<label for="password">Password</label>
			<input type="password" name="password" class="form-control" placeholder="Enter your password" required>

			<button type="submit" name="login" class="btn btn-primary">Login</button>

			<div class="text-center mt-3">
				<a href="forgot-password.php">Forgot Password?</a>
			</div>

			<div class="text-center mt-3">
				<a href="../index.php" class="btn btn-outline-light w-100 mt-2">Back to Home</a>
			</div>

			<div class="footer">
				<p style="color:#fff;">&copy; 2025 <strong>RaktNest</strong> | All Rights Reserved</p>
			</div>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
