<?php
session_start();
include('includes/config.php');

$successMsg = "";
$errorMsg = "";

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $newpassword_plain = trim($_POST['newpassword']);
    $confirmpassword = trim($_POST['confirmpassword']);

    // Validation
    if (empty($email) || empty($mobile) || empty($newpassword_plain) || empty($confirmpassword)) {
        $errorMsg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email address.";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $errorMsg = "Please enter a valid 10-digit mobile number.";
    } elseif ($newpassword_plain !== $confirmpassword) {
        $errorMsg = "New Password and Confirm Password do not match.";
    } else {
        $newpassword = md5($newpassword_plain);

        $sql = "SELECT Email FROM tbladmin WHERE Email=:email and MobileNumber=:mobile";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $con = "UPDATE tbladmin SET Password=:newpassword WHERE Email=:email AND MobileNumber=:mobile";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
            $chngpwd1->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();

            $successMsg = "Password has been changed successfully! Redirecting...";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 2000);
            </script>";
        } else {
            $errorMsg = "Email or Mobile Number not found in our records.";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RaktNest | Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                  url('fp.jpg') no-repeat center center/cover;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Poppins', sans-serif;
    }

    .card {
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

    .card:hover {
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
      padding: 10px 40px 10px 10px;
    }

    .password-wrapper {
      position: relative;
    }

    .password-wrapper i {
      position: absolute;
      top: 50%;
      right: 12px;
      transform: translateY(-50%);
      cursor: pointer;
      color: #888;
      transition: 0.3s;
    }

    .password-wrapper i:hover {
      color: #d63031;
    }

    .error-text {
      color: red;
      font-size: 13px;
      margin-top: 3px;
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

    .alert-success {
      background-color: #eafaf1;
      color: #155724;
      border: 1px solid #c3e6cb;
      font-weight: 600;
    }

    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      font-weight: 600;
    }
  </style>
</head>

<body>
  <div class="card">
    <h2>Forgot Password</h2>

    <?php if (!empty($errorMsg)): ?>
      <div class="alert alert-danger text-center py-2"><?php echo htmlentities($errorMsg); ?></div>
    <?php endif; ?>

    <?php if (!empty($successMsg)): ?>
      <div class="alert alert-success text-center py-2"><?php echo htmlentities($successMsg); ?></div>
    <?php endif; ?>

    <form method="post" name="chngpwd" onsubmit="return validateForm();">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" class="form-control" placeholder="Enter Email" name="email" id="email">
        <div id="emailError" class="error-text"></div>
      </div>

      <div class="mb-3">
        <label>Mobile Number</label>
        <input type="text" class="form-control" placeholder="Enter Mobile Number" name="mobile" id="mobile" maxlength="10">
        <div id="mobileError" class="error-text"></div>
      </div>

      <div class="mb-3">
        <label>New Password</label>
        <div class="password-wrapper">
          <input type="password" class="form-control" placeholder="New Password" name="newpassword" id="newpassword">
          <i class="bi bi-eye-slash" id="toggleNewPassword"></i>
        </div>
        <div id="newpasswordError" class="error-text"></div>
      </div>

      <div class="mb-3">
        <label>Confirm Password</label>
        <div class="password-wrapper">
          <input type="password" class="form-control" placeholder="Confirm Password" name="confirmpassword" id="confirmpassword">
          <i class="bi bi-eye-slash" id="toggleConfirmPassword"></i>
        </div>
        <div id="confirmpasswordError" class="error-text"></div>
      </div>

      <button class="btn btn-primary w-100" type="submit" name="submit">Reset Password</button>

      <div class="text-center mt-3">
        <a href="../index.php">Back to Home</a>
      </div>
    </form>
  </div>

  <script>
    // ✅ Password show/hide toggles
    document.getElementById('toggleNewPassword').addEventListener('click', function() {
      const input = document.getElementById('newpassword');
      const icon = this;
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      } else {
        input.type = "password";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      }
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
      const input = document.getElementById('confirmpassword');
      const icon = this;
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      } else {
        input.type = "password";
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      }
    });

    // ✅ Validation
    function validateForm() {
      let valid = true;
      document.querySelectorAll('.error-text').forEach(e => e.innerText = "");

      let email = document.getElementById('email').value.trim();
      let mobile = document.getElementById('mobile').value.trim();
      let newpassword = document.getElementById('newpassword').value.trim();
      let confirmpassword = document.getElementById('confirmpassword').value.trim();

      const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
      const mobilePattern = /^[0-9]{10}$/;

      if (email === "") {
        document.getElementById('emailError').innerText = "Please enter your email.";
        valid = false;
      } else if (!emailPattern.test(email)) {
        document.getElementById('emailError').innerText = "Invalid email format.";
        valid = false;
      }

      if (mobile === "") {
        document.getElementById('mobileError').innerText = "Please enter your mobile number.";
        valid = false;
      } else if (!mobilePattern.test(mobile)) {
        document.getElementById('mobileError').innerText = "Invalid mobile number format.";
        valid = false;
      }

      if (newpassword === "") {
        document.getElementById('newpasswordError').innerText = "Please enter new password.";
        valid = false;
      }

      if (confirmpassword === "") {
        document.getElementById('confirmpasswordError').innerText = "Please confirm your password.";
        valid = false;
      } else if (newpassword !== confirmpassword) {
        document.getElementById('confirmpasswordError').innerText = "Passwords do not match.";
        valid = false;
      }

      return valid;
    }

    // Hide error when typing
    document.querySelectorAll("input").forEach(input => {
      input.addEventListener("input", function() {
        const errorElem = document.getElementById(this.id + "Error");
        if (errorElem) errorElem.innerText = "";
      });
    });
  </script>
</body>
</html>
