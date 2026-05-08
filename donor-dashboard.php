<?php
// ✅ Session settings for persistent login (24 hours)
session_set_cookie_params(24 * 60 * 60, "/", "", false, true);
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/sidebar.php');

// ✅ If session expired but cookie still exists, rebuild session
if (!isset($_SESSION['login']) && isset($_COOKIE['PHPSESSID'])) {
    session_start();
}

// ✅ Protect page (donor only)
if (empty($_SESSION['login']) || $_SESSION['role'] != 'donor') {
    header('location:login.php');
    exit();
}

// ✅ Get donor ID from database
$email = $_SESSION['login'];
$sql = "SELECT id FROM tblblooddonars WHERE EmailId = :email";
$query = $dbh->prepare($sql);
$query->bindParam(':email', $email, PDO::PARAM_STR);
$query->execute();
$donor = $query->fetch(PDO::FETCH_OBJ);
$did = $donor ? $donor->id : 0;
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RaktNest | Donor Dashboard</title>

	<!-- Bootstrap -->
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
		.sidebar i { margin-right: 10px; }

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
		.header .user-info i { color: #b71c1c; }
		.dashboard { padding: 30px; }
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
		@media (max-width: 992px) {
			.sidebar { position: relative; width: 100%; height: auto; }
			.main-content { margin-left: 0; }
		}
	</style>
</head>

<body>
	
	<!-- Sidebar -->
	<div class="sidebar">
		<h3><i class="fa-solid fa-droplet"></i> RaktNest</h3>
		<a href="donor-dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
				<a href="my-profile1.php"><i class="fa-solid fa-user-pen"></i> My Profile</a>
		<a href="receiver-list.php"><i class="fa-solid fa-hand-holding-heart"></i> Receiver List</a>
		<a href="donation-history.php"><i class="fa-solid fa-clock-rotate-left"></i> My Donations</a>

		<!-- <a href="view-bloodgroups.php"><i class="fa-solid fa-vials"></i> Blood Groups</a> -->
		<a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
	</div>

	<!-- Main Content -->
	<div class="main-content">
		<div class="header">
			<h4>Donor Dashboard</h4>
			<div class="user-info">
				<i class="fa-solid fa-user"></i>
				<span><?php echo htmlentities($_SESSION['login']); ?></span>
			</div>
		</div>

		<div class="dashboard container-fluid">
			<div class="row g-4">

				<!-- Requested Receivers -->
				<div class="col-md-6 col-xl-3">
					<div class="card">
						<div class="card-body">
							<?php 
							$sql = "SELECT id FROM tblbloodrequirer WHERE BloodDonarID = :did";
							$query = $dbh->prepare($sql);
							$query->bindParam(':did', $did, PDO::PARAM_INT);
							$query->execute();
							$totalReceivers = $query->rowCount();
							?>
							<div class="card-number"><?php echo htmlentities($totalReceivers);?></div>
							<div class="card-title">Requested Receivers</div>
						</div>
						<div class="card-footer">
							<a href="receiver-list.php">View Details <i class="fa fa-arrow-right"></i></a>
						</div>
					</div>
				</div>

				<!-- My Donations -->
				<div class="col-md-6 col-xl-3">
					<div class="card">
						<div class="card-body">
							<?php 
							$sql1 ="SELECT id FROM tbldonations WHERE DonorID=:did";
							$query1 = $dbh -> prepare($sql1);
							$query1->bindParam(':did',$did,PDO::PARAM_INT);
							$query1->execute();
							$myDonations=$query1->rowCount();
							?>
							<div class="card-number"><?php echo htmlentities($myDonations);?></div>
							<div class="card-title">My Donations</div>
						</div>
						<div class="card-footer">
							<a href="donation-history.php">View Details <i class="fa fa-arrow-right"></i></a>
						</div>
					</div>
				</div>

				<!-- Blood Groups -->
				<div class="col-md-6 col-xl-3">
					<div class="card">
						<div class="card-body">
							<?php 
							$sql2 ="SELECT id FROM tblbloodgroup";
							$query2 = $dbh -> prepare($sql2);
							$query2->execute();
							$bg=$query2->rowCount();
							?>
							<div class="card-number"><?php echo htmlentities($bg);?></div>
							<div class="card-title">Available Blood Groups</div>
						</div>
						<div class="card-footer">
							<a href="view-bloodgroups.php">View Details <i class="fa fa-arrow-right"></i></a>
						</div>
					</div>
				</div>

				<!-- Total Queries -->
				<div class="col-md-6 col-xl-3">
					<div class="card">
						<div class="card-body">
							<?php 
							$sql3 ="SELECT id FROM tblcontactusquery";
							$query3 = $dbh -> prepare($sql3);
							$query3->execute();
							$queries=$query3->rowCount();
							?>
							<div class="card-number"><?php echo htmlentities($queries);?></div>
							<div class="card-title">Total Queries</div>
						</div>
						<div class="card-footer">
							<a href="contact-queries.php">View Details <i class="fa fa-arrow-right"></i></a>
						</div>
					</div>
				</div>

			</div><!-- row -->
		</div><!-- dashboard -->
	</div><!-- main-content -->

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php  ?>
		