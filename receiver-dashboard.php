<?php
// ✅ SESSION STAY LOGGED IN FIX
ini_set('session.gc_maxlifetime', 86400); // 1 day = 86400 seconds
session_set_cookie_params([
    'lifetime' => 86400, // cookie valid for 1 day
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0 || $_SESSION['role']!='receiver'){	
	header('location:login.php');
	exit();
}
else{
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RaktNest | Receiver Dashboard</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

	<style>
		body {
			font-family: 'Poppins', sans-serif;
			background-color: #f5f6fa;
			margin: 0;
		}

		.sidebar {
			position: fixed;
			left: 0;
			top: 0;
			width: 240px;
			height: 100vh;
			background-color: #b71c1c;
			color: white;
			padding-top: 20px;
			box-shadow: 3px 0 15px rgba(0,0,0,0.2);
			z-index: 1000;
		}
		.sidebar h3 {
			text-align: center;
			font-weight: 700;
			margin-bottom: 30px;
		}
		.sidebar a {
			display: block;
			padding: 12px 25px;
			color: #fff;
			font-weight: 500;
			text-decoration: none;
			transition: all 0.3s ease;
		}
		.sidebar a:hover, .sidebar a.active {
			background-color: #d63031;
			padding-left: 30px;
		}
		.sidebar i {
			margin-right: 10px;
		}

		.main-content {
			margin-left: 240px;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		.header {
			background-color: #fff;
			padding: 15px 30px;
			display: flex;
			align-items: center;
			justify-content: space-between;
			box-shadow: 0 4px 15px rgba(0,0,0,0.1);
			position: sticky;
			top: 0;
			z-index: 500;
		}
		.header h4 {
			margin: 0;
			font-weight: 600;
			color: #b71c1c;
		}
		.header .user-info {
			display: flex;
			align-items: center;
			gap: 15px;
			font-weight: 500;
		}
		.header .user-info i {
			color: #b71c1c;
		}

		.dashboard {
			padding: 30px;
		}
		.card {
			border: none;
			border-radius: 15px;
			box-shadow: 0 4px 15px rgba(0,0,0,0.1);
			transition: transform 0.3s ease, box-shadow 0.3s ease;
		}
		.card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 25px rgba(0,0,0,0.15);
		}
		.card .card-body {
			text-align: center;
			padding: 30px;
		}
		.card .card-title {
			font-weight: 600;
			font-size: 18px;
			margin-top: 10px;
			color: #555;
		}
		.card .card-number {
			font-size: 36px;
			font-weight: 700;
			color: #b71c1c;
		}
		.card .card-footer {
			background-color: #f1f1f1;
			border-top: 1px solid #ddd;
			padding: 10px;
			text-align: center;
			border-radius: 0 0 15px 15px;
		}
		.card .card-footer a {
			text-decoration: none;
			color: #b71c1c;
			font-weight: 600;
		}
		.card .card-footer a:hover {
			text-decoration: underline;
		}

		.personal-info {
			margin-top: 40px;
		}
		.info-card {
			background-color: #fff;
			border-radius: 15px;
			box-shadow: 0 4px 15px rgba(0,0,0,0.1);
			padding: 25px 30px;
		}
		.info-card h5 {
			font-weight: 600;
			color: #b71c1c;
			margin-bottom: 20px;
		}
		.info-item {
			display: flex;
			justify-content: space-between;
			padding: 10px 0;
			border-bottom: 1px solid #eee;
			font-size: 15px;
		}
		.info-item:last-child {
			border-bottom: none;
		}
		.info-item span {
			color: #333;
			font-weight: 500;
		}
		.info-item i {
			color: #b71c1c;
			margin-right: 8px;
		}

		@media (max-width: 992px) {
			.sidebar {
				position: relative;
				width: 100%;
				height: auto;
			}
			.main-content {
				margin-left: 0;
			}
		}
	</style>
</head>

<body>
	<!-- Sidebar -->
	<div class="sidebar">
		<?php include('includes/sidebar.php'); ?>
	</div>

	<!-- Main Content -->
	<div class="main-content">
		<div class="header">
			<h4>Receiver Dashboard</h4>
			<div class="user-info">
				<i class="fa-solid fa-user"></i>
				<span><?php echo htmlentities($_SESSION['login']); ?></span>
			</div>
		</div>

		<div class="dashboard container-fluid">
			<div class="row g-4">
				<!-- Available Donors -->
				<div class="col-md-6 col-xl-3">
					<div class="card">
						<div class="card-body">
							<?php 
							$status = 1;
							$sql = "SELECT id FROM tblblooddonars WHERE status = :status";
							$query = $dbh->prepare($sql);
							$query->bindParam(':status', $status, PDO::PARAM_STR);
							$query->execute();
							$totalDonors = $query->rowCount();
							?>
							<div class="card-number"><?php echo htmlentities($totalDonors);?></div>
							<div class="card-title">Available Donors</div>
						</div>
						<div class="card-footer">
							<a href="donor-list-dashboard.php">View Details <i class="fa fa-arrow-right"></i></a>
						</div>
					</div>
				</div>

				<!-- My Requests -->
				<div class="col-md-6 col-xl-3">
					<div class="card">
						<div class="card-body">
							<?php 
							$rid=$_SESSION['id'];
							$sql1 ="SELECT id from tblbloodrequirer where RequirerID=:rid";
							$query1 = $dbh -> prepare($sql1);
							$query1->bindParam(':rid',$rid,PDO::PARAM_STR);
							$query1->execute();
							$myRequests=$query1->rowCount();
							?>
							<div class="card-number"><?php echo htmlentities($myRequests);?></div>
							<div class="card-title">My Blood Requests</div>
						</div>
						<div class="card-footer">
							<a href="track-request.php">View Details <i class="fa fa-arrow-right"></i></a>
						</div>
					</div>
				</div>

				<!-- Donors Contacted -->
				<div class="col-md-6 col-xl-3">
					<div class="card">
						<div class="card-body">
							<?php 
							$sql5 ="SELECT id from tblcontactblood";
							$query5 = $dbh -> prepare($sql5);
							$query5->execute();
							$contacted=$query5->rowCount();
							?>
							<div class="card-number"><?php echo htmlentities($contacted);?></div>
							<div class="card-title">Donors Contacted</div>
						</div>
						<div class="card-footer">
							<a href="contact-blood.php">View Details <i class="fa fa-arrow-right"></i></a>
						</div>
					</div>
				</div>
			</div><!-- row -->

			<!-- Personal Information Section -->
			<div class="personal-info container mt-5">
				<div class="info-card">
					<h5><i class="fa-solid fa-id-card"></i> Personal Information</h5>
					<div class="info-item"><i class="fa-solid fa-user"></i> <span>Name:</span> <span><?php echo htmlentities($_SESSION['fullname']); ?></span></div>
					<div class="info-item"><i class="fa-solid fa-envelope"></i> <span>Email:</span> <span><?php echo htmlentities($_SESSION['login']); ?></span></div>
					<div class="info-item"><i class="fa-solid fa-phone"></i> <span>Phone:</span> <span><?php echo htmlentities($_SESSION['phone']); ?></span></div>
					<div class="info-item"><i class="fa-solid fa-droplet"></i> <span>Blood Group:</span> <span><?php echo htmlentities($_SESSION['bloodgroup']); ?></span></div>
					<div class="info-item"><i class="fa-solid fa-location-dot"></i> <span>City:</span> <span><?php echo htmlentities($_SESSION['city']); ?></span></div>
				</div>
			</div>

		</div><!-- dashboard -->
	</div><!-- main-content -->

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>
