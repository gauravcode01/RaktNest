<?php
session_start();
error_reporting(0);
include('includes/config.php');

// ✅ Check login and role
if(strlen($_SESSION['login'])==0 || $_SESSION['role']!='receiver'){	
	header('location:login.php');
	exit();
}
include('includes/sidebar.php');
$rid = $_SESSION['id']; // Receiver ID
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RaktNest | Track My Requests</title>

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
		.main-content {
			margin-left: 240px;
			padding: 30px;
		}
		h4 {
			color: #b71c1c;
			font-weight: 600;
			margin-bottom: 25px;
		}
		.table {
			background: #fff;
			box-shadow: 0 4px 10px rgba(0,0,0,0.1);
			border-radius: 10px;
			overflow: hidden;
		}
		th {
			background-color: #b71c1c;
			color: white;
			text-align: center;
		}
		td {
			text-align: center;
			vertical-align: middle;
		}
		.badge {
			font-size: 0.9em;
			padding: 6px 10px;
			border-radius: 6px;
		}
	</style>
</head>

<body>
	<!-- Sidebar -->
	<div class="sidebar">
		<h3><i class="fa-solid fa-droplet"></i> RaktNest</h3>
		<a href="receiver-dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
		<a href="my-profile.php"><i class="fa-solid fa-user-circle"></i> My Profile</a>
		<!-- <a href="request-blood.php"><i class="fa-solid fa-hand-holding-medical"></i> Request Blood</a> -->
		<a href="track-request.php" class="active"><i class="fa-solid fa-route"></i> Track Blood Request</a>
		<!-- <a href="need-help.php"><i class="fa-solid fa-message"></i> Need Help</a> -->
		<a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
	</div>

	<!-- Main Content -->
	<div class="main-content">
		<h4>My Blood Requests Status</h4>

		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Donor Name</th>
						<th>Donor Email</th>
						<th>Donor Contact</th>
						<th>Blood Required For</th>
						<th>Message</th>
						<th>Request Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				// ✅ Explicitly alias "Status" column to avoid case-sensitivity issues
				$sql = "SELECT 
							r.ID,
							r.name,
							r.EmailId,
							r.ContactNumber,
							r.BloodRequirefor,
							r.Message,
							r.ApplyDate,
							r.Status AS ReqStatus,
							d.FullName AS DonorName,
							d.EmailId AS DonorEmail,
							d.MobileNumber AS DonorContact
						FROM tblbloodrequirer r
						INNER JOIN tblblooddonars d ON d.ID = r.BloodDonarID
						WHERE r.RequirerID = :rid
						ORDER BY r.ID DESC";

				$query = $dbh->prepare($sql);
				$query->bindParam(':rid',$rid,PDO::PARAM_INT);
				$query->execute();
				$results = $query->fetchAll(PDO::FETCH_OBJ);
				$cnt = 1;

				if($query->rowCount() > 0){
					foreach($results as $row){ 
						$statusText = $row->ReqStatus ? $row->ReqStatus : 'Pending';
						$statusColor = 'secondary';
						if($statusText == 'Accepted') $statusColor = 'success';
						elseif($statusText == 'Rejected') $statusColor = 'danger';
				?>
						<tr>
							<td><?php echo htmlentities($cnt++); ?></td>
							<td><?php echo htmlentities($row->DonorName); ?></td>
							<td><?php echo htmlentities($row->DonorEmail); ?></td>
							<td><?php echo htmlentities($row->DonorContact); ?></td>
							<td><?php echo htmlentities($row->BloodRequirefor); ?></td>
							<td><?php echo htmlentities($row->Message); ?></td>
							<td><?php echo htmlentities($row->ApplyDate); ?></td>
							<td><span class="badge bg-<?php echo $statusColor; ?>"><?php echo htmlentities($statusText); ?></span></td>
						</tr>
				<?php 
					}
				} else { ?>
					<tr>
						<td colspan="8" class="text-center text-muted">You haven't made any blood requests yet.</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
