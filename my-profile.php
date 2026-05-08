<?php
session_start();
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) { 
  header('location:login.php');
  exit;
} else {
  $email = $_SESSION['login'];
  $role = $_SESSION['role'];

  // Select table according to user role
  $table = ($role == "donor") ? "tblblooddonars" : "tblreceivers";

  // Handle Update Request
  if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $bloodgroup = $_POST['bloodgroup'];
    $weight = $_POST['weight'];
    // $lastDonation = $_POST['lastdonation'];
    $lifestyle = $_POST['lifestyle'];

    $sql = "UPDATE $table 
            SET FullName=:fullname, MobileNumber=:mobile, Gender=:gender, Age=:age, Address=:address,
                BloodGroup=:bloodgroup,Lifestyle=:lifestyle
            WHERE EmailId=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':gender', $gender, PDO::PARAM_STR);
    $query->bindParam(':age', $age, PDO::PARAM_INT);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':bloodgroup', $bloodgroup, PDO::PARAM_STR);
    // $query->bindParam(':lastdonation', $lastDonation, PDO::PARAM_STR);
    $query->bindParam(':lifestyle', $lifestyle, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    echo "<script>alert('Profile updated successfully!'); window.location.href='my-profile.php';</script>";
  }

  // Fetch Updated Data
  $sql = "SELECT * FROM $table WHERE EmailId = :email";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->execute();
  $result = $query->fetch(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile | RaktNest</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
  background-color: #f8f9fa;
  font-family: 'Poppins', sans-serif;
}
.sidebar {
	position: fixed; left: 0; top: 0; width: 240px; height: 100vh;
	background-color: #b71c1c; color: white; padding-top: 20px;
	box-shadow: 3px 0 15px rgba(0,0,0,0.2);
}
.sidebar h3 { text-align: center; font-weight: 700; margin-bottom: 30px; }
.sidebar a {
	display: block; padding: 12px 25px; color: #fff; font-weight: 500;
	text-decoration: none; transition: all 0.3s ease;
}
.sidebar a:hover, .sidebar a.active { background-color: #d63031; padding-left: 30px; }
.main-content { margin-left: 240px; padding: 40px; }
.profile-card {
  max-width: 750px; margin: 0 auto; background: #fff; border-radius: 15px;
  box-shadow: 0 0 30px rgba(255,0,0,0.25); padding: 35px 40px;
}
.profile-card h3 { color: #d63031; text-align: center; font-weight: 700; }
.label { font-weight: bold; color: #555; }
.btn-edit, .btn-save, .btn-cancel {
  display: inline-block; margin-top: 20px; border: none; padding: 10px 25px;
  border-radius: 8px; font-weight: 600; transition: 0.3s; color: #fff;
}
.btn-edit { background-color: #d63031; }
.btn-edit:hover { background-color: #b71c1c; }
.btn-save { background-color: #2e7d32; }
.btn-save:hover { background-color: #1b5e20; }
.btn-cancel { background-color: #757575; margin-left: 10px; }
.btn-cancel:hover { background-color: #424242; }

/* Input Styling */
input, select, textarea {
  width: 100%; border: 1px solid #ccc; border-radius: 6px; padding: 6px 10px;
}

/* ⭐ HIDE BORDER IN READONLY MODE */
.no-border {
  border: none !important;
  background: transparent !important;
  padding-left: 0 !important;
}
</style>
</head>

<body>
<?php include('includes/sidebar.php'); ?>

	<div class="sidebar">
		<h3><i class="fa-solid fa-droplet"></i> RaktNest</h3>
		<a href="receiver-dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a>
		<a href="my-profile.php"><i class="fa-solid fa-user-circle"></i> My Profile</a>
		<!-- <a href="request-blood.php"><i class="fa-solid fa-hand-holding-medical"></i> Request Blood</a> -->
		<a href="track-request.php" class="active"><i class="fa-solid fa-route"></i> Track Blood Request</a>
		<!-- <a href="need-help.php"><i class="fa-solid fa-message"></i> Need Help</a> -->
		<a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
	</div>

<div class="main-content">
  <div class="profile-card">
    <?php if($result): ?>
      <form method="POST">
        <h3>My Profile</h3>
        <hr>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="label">Full Name</label>
            <input class="no-border" type="text" name="fullname" value="<?php echo htmlentities($result->FullName); ?>" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label class="label">Email ID</label>
            <input class="no-border" type="email" value="<?php echo htmlentities($result->EmailId); ?>" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label class="label">Mobile Number</label>
            <input class="no-border" type="text" name="mobile" value="<?php echo htmlentities($result->MobileNumber); ?>" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label class="label">Gender</label>
            <select class="no-border" name="gender" disabled>
              <option <?php if($result->Gender=='Male') echo 'selected'; ?>>Male</option>
              <option <?php if($result->Gender=='Female') echo 'selected'; ?>>Female</option>
              <option <?php if($result->Gender=='Other') echo 'selected'; ?>>Other</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="label">Age</label>
            <input class="no-border" type="number" name="age" value="<?php echo htmlentities($result->Age); ?>" readonly>
          </div>
          <div class="col-md-6 mb-3">
            <label class="label">Address</label>
            <textarea class="no-border" name="address" rows="2" readonly><?php echo htmlentities($result->Address); ?></textarea>
          </div>
        </div>

        <h5 class="mt-4 text-danger">Medical Info</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="label">Blood Group</label>
            <input class="no-border" type="text" name="bloodgroup" value="<?php echo htmlentities($result->BloodGroup); ?>" readonly>
          </div>
<!-- 
          <div class="col-md-6 mb-3">
            <label class="label">Last Donation Date</label>
            <input class="no-border" type="date" name="lastdonation" value="<?php echo htmlentities($result->LastDonationDate); ?>" readonly>
          </div>
        </div> -->

        <h5 class="mt-4 text-danger">Medical History</h5>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="label">Chronic Illnesses</label>
            <select class="no-border" name="chronic" disabled>
              <option value="No">No</option>
              <option value="Yes">Yes</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="label">Infectious Diseases</label>
            <select class="no-border" name="infectious" disabled>
              <option value="No">No</option>
              <option value="Yes">Yes</option>
            </select>
          </div>
          <div class="col-md-4 mb-3">
            <label class="label">Current Medications</label>
            <select class="no-border" name="medications" disabled>
              <option value="No">No</option>
              <option value="Yes">Yes</option>
            </select>
          </div>
        </div>

        <h5 class="mt-4 text-danger">Lifestyle</h5>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="label">Lifestyle</label>
            <input class="no-border" type="text" name="lifestyle" value="<?php echo htmlentities($result->Lifestyle); ?>" readonly>
          </div>
        </div>

        <div class="text-center">
          <button type="button" id="editBtn" class="btn-edit"><i class="fa-solid fa-pen"></i> Edit Profile</button>
          <button type="submit" name="update" id="saveBtn" class="btn-save d-none"><i class="fa-solid fa-save"></i> Save</button>
          <button type="button" id="cancelBtn" class="btn-cancel d-none"><i class="fa-solid fa-xmark"></i> Cancel</button>
        </div>
      </form>
    <?php else: ?>
      <p class="text-center text-danger">No user details found.</p>
    <?php endif; ?>
  </div>
</div>

<script>
// ⭐ Edit Mode → Borders visible
document.getElementById('editBtn').addEventListener('click', function() {
  
  document.querySelectorAll('input, select, textarea').forEach(el => el.removeAttribute('readonly'));
  document.querySelectorAll('select').forEach(el => el.removeAttribute('disabled'));

  // Remove border hiding class → border will show
  document.querySelectorAll('input, textarea, select').forEach(el => {
      el.classList.remove('no-border');
  });

  document.getElementById('editBtn').classList.add('d-none');
  document.getElementById('saveBtn').classList.remove('d-none');
  document.getElementById('cancelBtn').classList.remove('d-none');
});

// Cancel Reset
document.getElementById('cancelBtn').addEventListener('click', function() {
  window.location.href = 'my-profile.php';
});
</script>

</body>
</html>
