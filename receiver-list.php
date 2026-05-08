<?php
session_start();
error_reporting(0);
include('includes/config.php');
include('includes/sidebar.php');
// ✅ Step 1: Login check
if(strlen($_SESSION['login'])==0 || $_SESSION['role']!='donor'){	
	header('location:login.php');
	exit();
}

$did = $_SESSION['id']; // Donor ID

// ✅ Step 2: Accept / Reject request action
// ✅ Step 2: Accept / Reject request action
if(isset($_POST['accept']) || isset($_POST['reject'])){
    $status = isset($_POST['accept']) ? 'Accepted' : 'Rejected';
    $reqid = intval($_POST['reqid']); // ensure it's integer

    // Debug output (check what ID is being received)
    echo "<script>console.log('Updating Request ID: $reqid');</script>";

    // ✅ Update status correctly
    $sql = "UPDATE tblbloodrequirer SET Status = :status WHERE ID = :reqid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->bindParam(':reqid', $reqid, PDO::PARAM_INT);
    $query->execute();

    if($query->rowCount() > 0){
        echo "<script>alert('Request $status successfully!'); window.location.href='receiver-list.php';</script>";
        exit();
    } else {
        echo "<script>alert('⚠️ No record updated. Check if ID in form matches table ID.');</script>";
    }
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RaktNest | Receiver List</title>

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
		.btn-sm {
			padding: 5px 10px;
			border-radius: 6px;
			font-size: 13px;
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
		<a href="donor-dashboard.php" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a>
				<a href="my-profile1.php"><i class="fa-solid fa-user-pen"></i> My Profile</a>
		<a href="receiver-list.php"><i class="fa-solid fa-hand-holding-heart"></i> Receiver List</a>
		<a href="donation-history.php"><i class="fa-solid fa-clock-rotate-left"></i> My Donations</a>

		<!-- <a href="view-bloodgroups.php"><i class="fa-solid fa-vials"></i> Blood Groups</a> -->
		<a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
	</div>

	<!-- Main Content -->
	<div class="main-content">
		<h4>Receivers Who Requested You</h4>

		<div class="table-responsive">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Email</th>
						<th>Contact</th>
						<th>Blood Required For</th>
						<th>Message</th>
						<th>Applied Date</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$sql = "SELECT * FROM tblbloodrequirer WHERE BloodDonarID = :did ORDER BY id DESC";
				$query = $dbh->prepare($sql);
				$query->bindParam(':did',$did,PDO::PARAM_INT);
				$query->execute();
				$results = $query->fetchAll(PDO::FETCH_OBJ);
				$cnt = 1;

				if($query->rowCount() > 0){
					foreach($results as $row){ ?>
						<tr>
							<td><?php echo htmlentities($cnt++); ?></td>
							<td><?php echo htmlentities($row->name); ?></td>
							<td><?php echo htmlentities($row->EmailId); ?></td>
							<td><?php echo htmlentities($row->ContactNumber); ?></td>
							<td><?php echo htmlentities($row->BloodRequirefor); ?></td>
							<td><?php echo htmlentities($row->Message); ?></td>
							<td><?php echo htmlentities($row->ApplyDate); ?></td>
							<td>
								<span class="badge bg-<?php 
									echo ($row->Status == 'Accepted') ? 'success' : 
										(($row->Status == 'Rejected') ? 'danger' : 'secondary'); 
								?>">
									<?php echo htmlentities($row->Status ? $row->Status : 'Pending'); ?>
								</span>
							</td>
							<td>
								<?php if($row->Status == 'Pending'){ ?>
									<form method="POST" style="display:inline;">
										<input type="hidden" name="reqid" value="<?php echo $row->ID; ?>">
										<button type="submit" name="accept" class="btn btn-success btn-sm">Accept</button>
										<button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>
									</form>
								<?php } else { ?>
									<em class="text-muted">No Action</em>
								<?php } ?>
							</td>
						</tr>
				<?php } } else { ?>
						<tr>
							<td colspan="9" class="text-center text-muted">No blood requests found yet.</td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

	<!-- JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
