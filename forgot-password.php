<?php
session_start();
include('includes/config.php');

if(isset($_POST['submit']))
{
  $email=$_POST['email'];
  $newpassword=md5($_POST['newpassword']);
  $sql ="SELECT EmailId FROM tblblooddonars WHERE EmailId=:email";
  $query= $dbh -> prepare($sql);
  $query-> bindParam(':email', $email, PDO::PARAM_STR);
  $query-> execute();

  if($query->rowCount() > 0)
  {
    $con="UPDATE tblblooddonars SET Password=:newpassword WHERE EmailId=:email";
    $chngpwd1 = $dbh->prepare($con);
    $chngpwd1-> bindParam(':email', $email, PDO::PARAM_STR);
    $chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
    $chngpwd1-> execute();
    echo "<script>alert('Password Changed Successfully');</script>";
  } 
  else {
    echo "<script>alert('Invalid Email ID');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password | RaktNest</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fff;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .card {
      border: none;
      border-radius: 20px;
      background: #ffffff;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 0 25px rgba(255, 0, 0, 0.15), 
                  0 0 45px rgba(255, 0, 0, 0.2);
      transition: 0.4s ease;
    }

    .card:hover {
      box-shadow: 0 0 35px rgba(255, 0, 0, 0.25), 
                  0 0 60px rgba(255, 0, 0, 0.35);
      transform: translateY(-4px);
    }

    .card-header {
      background: linear-gradient(90deg, #ff0000, #ff4d4d);
      color: white;
      border-radius: 20px 20px 0 0;
      text-align: center;
      padding: 25px 10px;
    }

    .icon {
      font-size: 55px;
      color: #ffffff;
      margin-bottom: 8px;
    }

    .card-body {
      padding: 35px;
    }

    .form-label {
      font-weight: 500;
      color: #333;
      margin-bottom: 5px;
    }

    .form-control {
      border-radius: 12px;
      border: 1px solid #ccc;
      padding: 10px 14px;
      transition: 0.3s;
    }

    .form-control:focus {
      border-color: #ff0000;
      box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
    }

    .btn-brand {
      background: linear-gradient(90deg, #ff0000, #ff4d4d);
      color: white;
      border: none;
      font-weight: 600;
      border-radius: 12px;
      padding: 12px;
      width: 100%;
      transition: 0.3s;
    }

    .btn-brand:hover {
      background: linear-gradient(90deg, #e60000, #ff3333);
      transform: translateY(-2px);
      box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
    }

    .text-link {
      color: #ff0000;
      font-weight: 500;
      text-decoration: none;
    }

    .text-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 576px) {
      .card-body {
        padding: 25px;
      }
    }
  </style>
</head>

<body>
  <div class="card">
    <div class="card-header">
      <i class="fa-solid fa-unlock-keyhole icon"></i>
      <h3>Forgot Password</h3>
    </div>

    <div class="card-body">
      <form method="post">

        <div class="mb-4">
          <label class="form-label">Registered Email</label>
          <input 
            type="email" 
            name="email" 
            placeholder="Enter your registered email" 
            class="form-control" 
            required
          >
        </div>

        <div class="mb-4">
          <label class="form-label">New Password</label>
          <input 
            type="password" 
            name="newpassword" 
            placeholder="Enter new password" 
            class="form-control" 
            required
          >
        </div>

        <button class="btn btn-brand" name="submit" type="submit">
          Change Password
        </button>

      </form>

      <div class="text-center mt-3">
        <a href="login.php" class="text-link">
          <i class="fa-solid fa-arrow-left"></i> Back to Login
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
