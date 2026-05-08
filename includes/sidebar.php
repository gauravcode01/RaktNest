<style>
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
@media (max-width: 992px) {
	.sidebar {
		position: relative;
		width: 100%;
		height: auto;
	}
}
</style>

<div class="sidebar">
	<h3><i class="fa-solid fa-droplet"></i> RaktNest</h3>
	<a href="receiver-dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'receiver-dashboard.php' ? 'active' : ''; ?>">
		<i class="fa-solid fa-gauge"></i> Dashboard
	</a>
	<a href="my-profile.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'my-profile.php' ? 'active' : ''; ?>">
		<i class="fa-solid fa-user-circle"></i> My Profile
	</a>
	<!-- <a href="request-blood.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'request-blood.php' ? 'active' : ''; ?>">
		<i class="fa-solid fa-hand-holding-medical"></i> Request Blood
	</a> -->
	<a href="track-request.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'track-request.php' ? 'active' : ''; ?>">
		<i class="fa-solid fa-route"></i> Track Blood Request
	</a>
	<!-- <a href="need-help.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'need-help.php' ? 'active' : ''; ?>">
		<i class="fa-solid fa-message"></i> Need Help
	</a> -->
	<a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
</div>
