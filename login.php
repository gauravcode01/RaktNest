<?php
session_start();
error_reporting(0);
include('includes/config.php');

// ✅ Show message from signup.php if exists
$signupMsg = "";
if (isset($_SESSION['signup_msg'])) {
    $signupMsg = $_SESSION['signup_msg'];
    unset($_SESSION['signup_msg']); // clear message after showing once
}

$errorMsg = ""; // error message to display to user

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password_plain = $_POST['password'];
    $password = md5($password_plain);
    $role = isset($_POST['role']) ? trim($_POST['role']) : '';

    // basic validation
    if (empty($role)) {
        $errorMsg = "Please select a role to login.";
    } elseif (empty($email) || empty($password_plain)) {
        $errorMsg = "Please enter both email and password.";
    } else {
        // determine table names
        $donorTable = "tblblooddonars";
        $receiverTable = "tblreceivers";

        // helper: check if email exists in a given table
        $existsIn = function($table, $email) use ($dbh) {
            $sql = "SELECT id FROM {$table} WHERE EmailId = :email LIMIT 1";
            $q = $dbh->prepare($sql);
            $q->bindParam(':email', $email, PDO::PARAM_STR);
            $q->execute();
            return $q->rowCount() > 0 ? $q->fetch(PDO::FETCH_OBJ) : false;
        };

        // check selected role table first for email existence
        if ($role === 'donor') {
            $table = $donorTable;
            $otherTable = $receiverTable;
            $roleLabel = "Blood Donor";
            $otherRoleLabel = "Blood Receiver";
        } else { // receiver
            $table = $receiverTable;
            $otherTable = $donorTable;
            $roleLabel = "Blood Receiver";
            $otherRoleLabel = "Blood Donor";
        }

        // Does email exist in selected role table?
        $existsSelected = $existsIn($table, $email);

        if ($existsSelected) {
            // Now verify password for selected role
            $sql = "SELECT * FROM {$table} WHERE EmailId = :email AND Password = :password LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':password', $password, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0) {
                // success
                $result = $query->fetch(PDO::FETCH_OBJ);

                $_SESSION['login'] = $email;
                $_SESSION['role'] = $role;
                $_SESSION['id'] = $result->id;

                // debug console (optional)
                echo "<script>console.log('Login successful! ID: " . addslashes($_SESSION['id']) . "');</script>";

                if ($role === 'donor') {
                    echo "<script>window.location.href='donor-dashboard.php';</script>";
                } else {
                    echo "<script>window.location.href='receiver-dashboard.php';</script>";
                }
                exit;
            } else {
                // email exists but password mismatch
                $errorMsg = "Invalid email or password.";
            }
        } else {
            // email does not exist in selected role table
            // check if email exists in the other role table to suggest switching role
            $existsOther = $existsIn($otherTable, $email);
            if ($existsOther) {
                $errorMsg = "This email is not registered as a {$roleLabel}. Please try selecting the other role.";
            } else {
                $errorMsg = "Email not found. Please register first.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>RaktNest | User Login</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

	<style>
		body {
			margin: 0;
			padding: 0;
			min-height: 100vh;
			background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
						url('img/bloodbg.jpg') no-repeat center center/cover;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Poppins', sans-serif;
		}

		.login-card {
			position: relative;
			background: rgba(255, 255, 255, 0.97);
			border-radius: 15px;
			padding: 40px 35px;
			width: 100%;
			max-width: 420px;
			backdrop-filter: blur(5px);
			box-shadow: 0 0 25px rgba(214, 48, 49, 0.25), 
						0 0 45px rgba(214, 48, 49, 0.15);
			transition: all 0.3s ease;
		}

		.login-card:hover {
			box-shadow: 0 0 35px rgba(214, 48, 49, 0.3),
						0 0 60px rgba(214, 48, 49, 0.2);
			transform: translateY(-3px);
		}

		h2 {
			text-align: center;
			margin-bottom: 25px;
			font-weight: 700;
			color: #d63031;
			letter-spacing: 1px;
		}

		label {
			font-weight: 600;
			color: #333;
			margin-top: 10px;
		}

		.form-control {
			border-radius: 10px;
			border: 1px solid #ccc;
			padding: 10px;
		}

		.btn-primary {
			background-color: #d63031;
			border: none;
			border-radius: 10px;
			padding: 10px;
			font-weight: 600;
			transition: 0.3s;
		}
		.btn-primary:hover {
			background-color: #b71c1c;
			box-shadow: 0 0 15px rgba(214,48,49,0.5);
		}

		.text-center a {
			color: #d63031;
			text-decoration: none;
			font-weight: 600;
		}
		.text-center a:hover {
			text-decoration: underline;
		}

		.footer {
			margin-top: 20px;
			font-size: 14px;
			color: #fff;
			text-align: center;
		}
	</style>
</head>

<body>
	<div class="login-card">
		<h2><i class="fa-solid fa-droplet"></i> RaktNest Login</h2>

		<!-- ✅ Signup message display -->
		<?php if (!empty($signupMsg)): ?>
			<div class="alert alert-warning text-center py-2">
				<?php echo htmlentities($signupMsg); ?>
			</div>
		<?php endif; ?>

		<!-- ✅ Error message display -->
		<?php if (!empty($errorMsg)): ?>
			<div class="alert alert-danger text-center py-2">
				<?php echo htmlentities($errorMsg); ?>
			</div>
		<?php endif; ?>

		<form method="POST">
			<div class="mb-3">
				<label for="role" class="form-label">Login As</label>
				<select name="role" class="form-select" required>
					<option value="">-- Select Role --</option>
					<option value="donor" <?php if(isset($_POST['role']) && $_POST['role']=='donor') echo 'selected'; ?>>Blood Donor</option>
					<option value="receiver" <?php if(isset($_POST['role']) && $_POST['role']=='receiver') echo 'selected'; ?>>Blood Receiver</option>
				</select>
			</div>

			<div class="mb-3">
				<label for="email" class="form-label">Email</label>
				<input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?php echo isset($_POST['email'])?htmlentities($_POST['email']):''; ?>">
			</div>

			<div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" name="password" class="form-control" placeholder="Enter your password" required>
			</div>

			<button type="submit" name="login" class="btn btn-primary w-100 mt-2">Login</button>

			<div class="text-center mt-3">
				<a href="forgot-password.php">Forgot Password?</a>
			</div>

			<div class="text-center mt-2">
				<p>Don’t have an account? <a href="sign-up.php">Register Here</a></p>
			</div>
			<div class="text-center mt-3">
				<a href="index.php" class="btn btn-outline-light w-100 mt-2" style="color:black;">Back to Home</a>
			</div>
			<div class="footer">
				<p style="color: blue;">&copy; 2025 <strong>RaktNest</strong> | All Rights Reserved</p>
			</div>
		</form>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
