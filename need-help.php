<?php
session_start();
error_reporting(0);
include('includes/config.php');

// ✅ Step 1: Access check (Receiver only)
if(strlen($_SESSION['login'])==0 || $_SESSION['role']!='receiver'){	
	header('location:login.php');
	exit();
}

// ✅ Step 2: Handle form submission
if(isset($_POST['submit'])) {
	$name = $_SESSION['fullname'];
	$email = $_SESSION['login'];
	$phone = $_SESSION['phone'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];

	$sql = "INSERT INTO tblhelpqueries (name, email, phone, subject, message) VALUES (:name, :email, :phone, :subject, :message)";
	$query = $dbh->prepare($sql);
	$query->bindParam(':name', $name, PDO::PARAM_STR);
	$query->bindParam(':email', $email, PDO::PARAM_STR);
	$query->bindParam(':phone', $phone, PDO::PARAM_STR);
	$query->bindParam(':subject', $subject, PDO::PARAM_STR);
	$query->bindParam(':message', $message, PDO::PARAM_STR);
	$query->execute();

	$successMsg = "Your help request has been submitted successfully! We will contact you shortly through your email.";
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RaktNest | Need Help</title>

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
		.page-content {
			padding: 30px;
		}
		.form-card {
			background: #fff;
			padding: 30px;
			border-radius: 15px;
			box-shadow: 0 4px 15px rgba(0,0,0,0.1);
			max-width: 700px;
			margin: auto;
		}
		.form-card h5 {
			color: #b71c1c;
			font-weight: 600;
			margin-bottom: 20px;
			text-align: center;
		}
		label {
			font-weight: 500;
			color: #444;
		}
		.form-control {
			border-radius: 10px;
			box-shadow: none;
			border: 1px solid #ccc;
		}
		.btn-danger {
			background-color: #b71c1c;
			border: none;
			font-weight: 600;
			border-radius: 10px;
			padding: 10px 25px;
		}
		.btn-danger:hover {
			background-color: #d63031;
		}
		.alert-success {
			border-radius: 10px;
		}
		.text-danger {
			font-size: 0.9rem;
			margin-top: 5px;
			display: block;
			transition: opacity 0.3s ease;
		}
		@media(max-width: 992px) {
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
			<h4>Need Help</h4>
			<div class="user-info">
				<i class="fa-solid fa-user"></i>
				<span><?php echo htmlentities($_SESSION['login']); ?></span>
			</div>
		</div>

		<div class="page-content container-fluid">
			<div class="form-card">
				<h5><i class="fa-solid fa-hand-holding-heart"></i> Submit Your Help Request</h5>

				<?php if(isset($successMsg)) { ?>
					<div class="alert alert-success text-center">
						<i class="fa-solid fa-check-circle"></i> <?php echo htmlentities($successMsg); ?>
					</div>
				<?php } ?>

				<form method="POST" id="helpForm" novalidate>
					<div class="mb-3">
						<label for="email" class="form-label">Email</label>
						<input type="email" id="email" name="email" class="form-control" value="<?php echo htmlentities($_SESSION['login']); ?>" readonly>
						<small class="text-danger" id="emailError"></small>
					</div>
					<div class="mb-3">
						<label for="subject" class="form-label">Subject</label>
						<input type="text" name="subject" id="subject" class="form-control" placeholder="e.g. Need urgent blood, facing issue with donor contact...">
						<small class="text-danger" id="subjectError"></small>
					</div>
					<div class="mb-3">
						<label for="message" class="form-label">Message</label>
						<textarea name="message" id="message" class="form-control" rows="5" placeholder="Describe your issue or request here..."></textarea>
						<small class="text-danger" id="messageError"></small>
					</div>
					<div class="text-center">
						<button type="submit" name="submit" class="btn btn-danger"><i class="fa-solid fa-paper-plane"></i> Submit Request</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		// ✅ Client-side validation with live feedback
		const form = document.getElementById("helpForm");
		const email = document.getElementById("email");
		const subject = document.getElementById("subject");
		const message = document.getElementById("message");

		form.addEventListener("submit", function(e) {
			let valid = true;

			// Reset all error messages
			document.getElementById("emailError").textContent = "";
			document.getElementById("subjectError").textContent = "";
			document.getElementById("messageError").textContent = "";

			if (email.value.trim() === "") {
				document.getElementById("emailError").textContent = "This field cannot be empty. Please fill and submit.";
				valid = false;
			}
			if (subject.value.trim() === "") {
				document.getElementById("subjectError").textContent = "This field cannot be empty. Please fill and submit.";
				valid = false;
			}
			if (message.value.trim() === "") {
				document.getElementById("messageError").textContent = "This field cannot be empty. Please fill and submit.";
				valid = false;
			}

			if (!valid) e.preventDefault();
		});

		// 🧠 Hide red alert when user starts typing
		[email, subject, message].forEach(input => {
			input.addEventListener("input", () => {
				const errorEl = document.getElementById(input.id + "Error");
				if (errorEl.textContent !== "") {
					errorEl.textContent = "";
				}
			});
		});
	</script>
</body>
</html>
