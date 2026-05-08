<?php
session_start();
include('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ✅ SESSION LOGIN CHECK (no auto logout until manual logout) */
if (!isset($_SESSION['login']) || $_SESSION['login'] == '') {
    header('location:login.php');
    exit;
}

/* ✅ Receiver ID check */
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Session expired. Please login again.'); window.location='login.php';</script>";
    exit;
}

/* ✅ Handle blood request form */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $donorid   = $_POST['donorid'] ?? '';
    $fullname  = $_POST['fullname'] ?? '';
    $email     = $_POST['email'] ?? '';
    $contact   = $_POST['contact'] ?? '';
    $bloodfor  = $_POST['brf'] ?? '';
    $message   = $_POST['message'] ?? '';
    $receiverid = $_SESSION['id'] ?? 0;
    $status = "Pending";

    if (empty($fullname) || empty($email) || empty($contact) || empty($bloodfor) || empty($message)) {
        echo "Field missing";
        exit;
    }

    $sql = "INSERT INTO tblbloodrequirer 
            (BloodDonarID, RequirerID, Name, EmailId, ContactNumber, BloodRequirefor, Message, Status)
            VALUES (:donorid, :receiverid, :fullname, :email, :contact, :bloodfor, :message, :status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':donorid', $donorid, PDO::PARAM_INT);
    $query->bindParam(':receiverid', $receiverid, PDO::PARAM_INT);
    $query->bindParam(':fullname', $fullname, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':contact', $contact, PDO::PARAM_STR);
    $query->bindParam(':bloodfor', $bloodfor, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);

    if ($query->execute()) {
        echo "success";
    } else {
        echo "Database error";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Donors | RaktNest</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f6fa;
      font-family: 'Poppins', sans-serif;
      margin: 0;
    }
    .main-content {
      margin-left: 240px;
      padding: 20px;
    }
    .page-header {
      text-align: center;
      padding: 40px 0 10px;
    }
    .page-header h2 {
      color: #b71c1c;
      font-weight: 700;
    }
    .donor-card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background: #fff;
    }
    .donor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .donor-card img {
      height: 220px;
      object-fit: cover;
      width: 100%;
    }
    .donor-info {
      padding: 20px;
    }
    .donor-info h5 {
      color: #b71c1c;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .donor-info p {
      margin-bottom: 6px;
      font-size: 15px;
    }
    .btn-request {
      background-color: #b71c1c;
      border: none;
      border-radius: 30px;
      padding: 8px 25px;
      color: white;
      font-weight: 600;
      transition: background 0.3s;
    }
    .btn-request:hover {
      background-color: #d63031;
    }
    @media (max-width: 992px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>
<?php include('includes/sidebar.php'); ?>

<div class="main-content" id="mainContent">
  <div class="container my-5">
    <div class="page-header">
      <h2><i class="fa-solid fa-users"></i> Available Blood Donors</h2>
      <p class="text-muted">Find suitable donors near your city</p>
    </div>

    <div class="row g-4 mt-4">
      <?php
      $status=1;
      $sql = "SELECT * FROM tblblooddonars WHERE status=:status";
      $query = $dbh->prepare($sql);
      $query->bindParam(':status',$status,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);

      if($query->rowCount() > 0) {
        foreach($results as $result) {
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="card donor-card">
          <img src="images/blood-donor.jpg" alt="Blood Donor">
          <div class="donor-info">
            <h5><?php echo htmlentities($result->FullName); ?></h5>
            <p><i class="fa-solid fa-venus-mars"></i> <?php echo htmlentities($result->Gender); ?></p>
            <p><i class="fa-solid fa-droplet"></i> <?php echo htmlentities($result->BloodGroup); ?></p>
            <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlentities($result->Address); ?></p>
            <p><i class="fa-solid fa-user-clock"></i> Age: <?php echo htmlentities($result->Age); ?></p>
            <div class="text-center mt-3">
              <button class="btn btn-request openFormBtn" data-donor-id="<?php echo $result->id; ?>">
                <i class="fa-solid fa-hand-holding-droplet"></i> Request
              </button>
            </div>
          </div>
        </div>
      </div>
      <?php }} else { ?>
      <div class="col-12 text-center mt-5">
        <h5 class="text-muted">No active donors found!</h5>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- 🔴 Popup Modal -->
<div id="contactModal" class="modal-overlay">
  <div class="contact-card">
    <h2 class="text-center mb-4 fw-bold" style="color:#d63031;">
      <i class="fa-solid fa-droplet"></i> Request Blood
    </h2>

    <form id="bloodForm" method="post" novalidate>
      <input type="hidden" name="send" value="1">
      <input type="hidden" name="donorid" id="donorId">

      <div class="mb-3">
        <label class="form-label fw-semibold">Full Name</label>
        <input type="text" class="form-control" name="fullname">
        <div class="error-msg text-danger small mt-1"></div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" class="form-control" name="email">
        <div class="error-msg text-danger small mt-1"></div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Contact Number</label>
        <input type="tel" class="form-control" name="contact">
        <div class="error-msg text-danger small mt-1"></div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Blood Required For</label>
        <input type="text" class="form-control" name="brf">
        <div class="error-msg text-danger small mt-1"></div>
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Message</label>
        <textarea class="form-control" name="message" rows="3"></textarea>
        <div class="error-msg text-danger small mt-1"></div>
      </div>
      <br>
      <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-secondary closeForm">Cancel</button>
        <button type="submit" name="send" class="btn btn-primary">Send Request</button>
      </div>
    </form>
  </div>
</div>

<style>
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.5);
  backdrop-filter: blur(6px);
  display: none;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}
.contact-card {
  background: white;
  border-radius: 15px;
  padding: 35px;
  width: 90%;
  max-width: 480px;
  box-shadow: 0 0 25px rgba(214, 48, 49, 0.25);
  animation: fadeInUp 0.3s ease;
}
@keyframes fadeInUp {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll(".openFormBtn").forEach(btn => {
    btn.addEventListener("click", function() {
      const donorId = this.getAttribute("data-donor-id");
      document.getElementById("donorId").value = donorId;
      document.getElementById("contactModal").style.display = "flex";
      document.getElementById("mainContent").style.filter = "blur(5px)";
    });
  });

  document.querySelector(".closeForm").addEventListener("click", function() {
    document.getElementById("contactModal").style.display = "none";
    document.getElementById("mainContent").style.filter = "none";
  });

  const form = document.getElementById("bloodForm");
  form.addEventListener("submit", function(e) {
    e.preventDefault();

    let isValid = true;
    form.querySelectorAll(".error-msg").forEach(el => el.textContent = "");

    const fullname = form.fullname.value.trim();
    const email = form.email.value.trim();
    const contact = form.contact.value.trim();
    const brf = form.brf.value.trim();
    const message = form.message.value.trim();

    if (fullname === "") { form.fullname.nextElementSibling.textContent = "Full Name is required."; isValid = false; }
    if (email === "") { form.email.nextElementSibling.textContent = "Email is required."; isValid = false; }
    const phoneRegex = /^[0-9]{10}$/;
    if (contact === "") { form.contact.nextElementSibling.textContent = "Contact number is required."; isValid = false; }
    else if (!phoneRegex.test(contact)) { form.contact.nextElementSibling.textContent = "Enter a valid 10-digit number."; isValid = false; }
    if (brf === "") { form.brf.nextElementSibling.textContent = "Please specify who needs the blood."; isValid = false; }
    if (message === "") { form.message.nextElementSibling.textContent = "Message field cannot be empty."; isValid = false; }

    if (!isValid) return;

    const formData = new FormData(form);

    fetch("donor-list-dashboard.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      console.log("Server response:", data);
      if (data.trim() === "success") {
        alert("✅ Request sent successfully!");
        document.getElementById("contactModal").style.display = "none";
        document.getElementById("mainContent").style.filter = "none";
        form.reset();
      } else {
        alert("❌ Server says: " + data);
      }
    })
    .catch(err => alert("❌ Connection error: " + err));
  });
});
</script>

</body>
</html>
