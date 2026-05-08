<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['send']))
  {
$cid=$_GET['cid'];
$name=$_POST['fullname'];
$email=$_POST['email'];
$contactno=$_POST['contactno'];
$brf=$_POST['brf'];
$message=$_POST['message'];
$sql="INSERT INTO  tblbloodrequirer(BloodDonarID,name,EmailId,ContactNumber,BloodRequirefor,Message) VALUES(:cid,:name,:email,:contactno,:brf,:message)";
$query = $dbh->prepare($sql);
$query->bindParam(':cid',$cid,PDO::PARAM_STR);
$query->bindParam(':name',$name,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':contactno',$contactno,PDO::PARAM_STR);
$query->bindParam(':brf',$brf,PDO::PARAM_STR);
$query->bindParam(':message',$message,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{

echo '<script>alert("Request has been sent. We will contact you shortly.")</script>';
}
else 
{
echo "<script>alert('Something went wrong. Please try again.');</script>";  
}

}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>RaktNest | Blood Requirer </title>
    <!-- Meta tag Keywords -->
    
    <script>
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- Bootstrap-Core-CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <!-- Style-CSS -->
    <link rel="stylesheet" href="css/fontawesome-all.css">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese"
        rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese"
        rel="stylesheet">
</head>

<body>
    <?php include('includes/header.php');?>

    <!-- banner 2 -->
    <div class="inner-banner-w3ls">
        <div class="container">

        </div>
        <!-- //banner 2 -->
    </div>
    <!-- page details -->
    <div class="breadcrumb-agile">
        <div aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Blood Needed Person</li>
            </ol>
        </div>
    </div>
    <!-- //page details -->

    <!-- contact -->
<!-- contact -->
<!-- contact -->
<!-- contact -->
<div class="agileits-contact py-5" style="background: #fcfcfc;">
  <div class="container py-xl-5 py-lg-4">
    <div class="w3ls-titles text-center mb-5">
      <h3 class="title" style="font-weight:700; color:#d62828;">Contact For Blood</h3>
      <span><i class="fas fa-user-md" style="color:#d62828;"></i></span>
    </div>

    <div class="d-flex justify-content-center align-items-center">
      <div class="contact-card p-5 bg-white">
        <h2 class="text-center mb-4 fw-bold" style="color:#d63031;">
          <i class="fa-solid fa-droplet"></i> Request Blood
        </h2>

        <!-- Success message -->
        <div id="successMessage" class="alert alert-success text-center py-2" style="display:none;">
          ✅ Request has been sent successfully! We’ll contact you shortly.
        </div>

        <form id="bloodForm" method="post" class="contact-form">
          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" class="form-control" name="fullname" placeholder="Enter your full name" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Enter your email" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Contact Number</label>
            <input type="tel" class="form-control" name="contactno" placeholder="Enter your contact number" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Blood Required For</label>
            <input type="text" class="form-control" name="brf" placeholder="Enter patient name or relation" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Message</label>
            <textarea class="form-control" name="message" rows="4" placeholder="Enter your message" required></textarea>
          </div>

          <button type="submit" name="send" class="btn btn-primary w-100 mt-2">
            <i class="fas fa-paper-plane me-2"></i>Send Request
          </button>
        </form>

        <div class="text-center mt-4">
          <p class="text-muted">We’ll reach out as soon as possible after verifying donor availability.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- CSS (Same style as login.php card) -->
<style>
  body {
    font-family: 'Poppins', sans-serif;
  }

  .contact-card {
    position: relative;
    background: rgba(255, 255, 255, 0.97);
    border-radius: 15px;
    padding: 40px 35px;
    width: 100%;
    max-width: 460px;
    backdrop-filter: blur(5px);
    box-shadow: 0 0 25px rgba(214, 48, 49, 0.25),
                0 0 45px rgba(214, 48, 49, 0.15);
    transition: all 0.3s ease;
  }

  .contact-card:hover {
    box-shadow: 0 0 35px rgba(214, 48, 49, 0.3),
                0 0 60px rgba(214, 48, 49, 0.2);
    transform: translateY(-3px);
  }

  .contact-card h2 i {
    color: #d63031;
  }

  label {
    font-weight: 600;
    color: #333;
  }

  .form-control {
    border-radius: 10px;
    border: 1px solid #ccc;
    padding: 10px;
  }

  .form-control:focus {
    border-color: #d63031;
    box-shadow: 0 0 8px rgba(214, 48, 49, 0.3);
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
    box-shadow: 0 0 15px rgba(214, 48, 49, 0.5);
  }

  #successMessage {
    animation: fadeIn 0.5s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-8px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>

<!-- JS for success message -->
<script>
  document.querySelector("#bloodForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const msg = document.getElementById("successMessage");
    msg.style.display = "block";
    this.reset();
    setTimeout(() => msg.style.display = "none", 4000);
  });
</script>



    


    <?php include('includes/footer.php');?>

    <!-- Js files -->
    <!-- JavaScript -->
    <script src="js/jquery-2.2.3.min.js"></script>
    <!-- Default-JavaScript-File -->

    <!-- banner slider -->
    <script src="js/responsiveslides.min.js"></script>
    <script>
        $(function () {
            $("#slider4").responsiveSlides({
                auto: true,
                pager: true,
                nav: true,
                speed: 1000,
                namespace: "callbacks",
                before: function () {
                    $('.events').append("<li>before event fired.</li>");
                },
                after: function () {
                    $('.events').append("<li>after event fired.</li>");
                }
            });
        });
    </script>
    <!-- //banner slider -->

    <!-- fixed navigation -->
    <script src="js/fixed-nav.js"></script>
    <script src="js/SmoothScroll.min.js"></script>
    <!-- move-top -->
    <script src="js/move-top.js"></script>
    <!-- easing -->
    <script src="js/easing.js"></script>
    <!--  necessary snippets for few javascript files -->
    <script src="js/medic.js"></script>

    <script src="js/bootstrap.js"></script>
    <!-- Necessary-JavaScript-File-For-Bootstrap -->

    <!-- //Js files -->

</body>

</html>