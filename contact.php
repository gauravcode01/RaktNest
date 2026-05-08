<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(isset($_POST['send']))
  {
$name=$_POST['fullname'];
$email=$_POST['email'];
$contactno=$_POST['contactno'];
$message=$_POST['message'];
$sql="INSERT INTO  tblcontactusquery(name,EmailId,ContactNumber,Message) VALUES(:name,:email,:contactno,:message)";
$query = $dbh->prepare($sql);
$query->bindParam(':name',$name,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':contactno',$contactno,PDO::PARAM_STR);
$query->bindParam(':message',$message,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{

echo '<script>alert("Query Sent. We will contact you shortly.")</script>';
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
	<title>RaktNest | Contact Us </title>
	<!-- Meta tag Keywords -->
	
	<script>
		addEventListener("load", function () {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>
	<!--// Meta tag Keywords -->

	<!-- Custom-Files -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Bootstrap-Core-CSS -->
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<!-- Style-CSS -->
	<link rel="stylesheet" href="css/fontawesome-all.css">
	<!-- Font-Awesome-Icons-CSS -->
	<!-- //Custom-Files -->

	<!-- Web-Fonts -->
	<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese"
	    rel="stylesheet">
	<link href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese"
	    rel="stylesheet">
	<!-- //Web-Fonts -->

</head>

<body>
	<?php include('includes/header.php');?>
	
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
				<li class="breadcrumb-item active" aria-current="page">Contact Us</li>
			</ol>
		</div>
	</div>

<!-- contact -->
<!-- contact -->
<!-- contact -->
<!-- contact -->
 <div class = "bg-image"></div>
<div class="agileits-contact py-5 contact-section">
  <div class="container py-xl-5 py-lg-3">
    <div class="w3ls-titles text-center mb-5"></div>

    <div class="d-flex justify-content-center align-items-center">
      <div class="main">
        <form method="post" onsubmit="return validateContactForm()">
          <h5>Contact Us</h5>

          <div class="row">
            <div class="col-md-6">
              <label for="fullname">Full Name :</label>
              <input type="text" id="fullname" name="fullname" placeholder="Enter Your Name">
              <span id="nameError"></span>
            </div>
            <div class="col-md-6">
              <label for="contactno">Contact No :</label>
              <input type="text" id="contactno" name="contactno" placeholder="Enter Phone Number">
              <span id="phoneError"></span>
            </div>
          </div>

          <label for="email">Email :</label>
          <input type="text" id="email" name="email" placeholder="Enter Your Email">
          <span id="emailError"></span>

          <label for="message">Message :</label>
          <textarea id="message" name="message" rows="3" placeholder="Type your message here..."></textarea>
          <span id="msgError"></span>

          <button type="submit" name="send" class="submit-btn">Send Message</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Updated Styling -->
<style>
  body {
    background-size: cover;
  }

.agileits-contact {
  position: relative;
  background: url("wallhaven-m92yyy_1920x1080.png") center/cover no-repeat fixed;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 60px 0;
  overflow: hidden;
}

/* Blur only background layer */
.agileits-contact::before {
  content: "";
  position: absolute;
  inset: 0;
  background: inherit;
  filter: blur(10px);
  transform: scale(1.05); /* avoid edge blur cutoff */
  z-index: 0;
}

/* Keep the form clear above the blur */
.main {
  position: relative;
  z-index: 1;
  width: 700px;
  padding: 30px 50px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(20px);
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
  color: #fff;
  text-align: center;
  animation: fadeIn 1s ease-in-out;
}



  /* Wider, shorter glass card */
  .main {
    width: 700px;              /* wider */
    padding: 30px 50px;        /* balanced padding */
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(20px);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    color: #fff;
    text-align: center;
    animation: fadeIn 1s ease-in-out;
  }

  @keyframes fadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
  }

  .main h5 {
    font-size: 30px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #ffdf5d;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
  }

  .main img {
    border-radius: 50%;
    border: 3px solid #fff;
    margin-bottom: 15px;
    box-shadow: 0 0 15px rgba(255,255,255,0.6);
  }

  .main label {
    font-size: 16px;
    font-weight: bold;
    color: #f1f1f1;
    display: block;
    margin-top: 10px;
    text-align: left;
  }

  .main input[type="text"],
  .main textarea {
    width: 100%;
    padding: 10px 15px;
    margin-top: 5px;
    border: none;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
    font-size: 15px;
    color: #333;
    outline: none;
    transition: 0.3s;
    resize: none;
  }

  .main input:focus,
  .main textarea:focus {
    box-shadow: 0 0 10px #00e0ff;
  }

  .main span {
    font-size: 13px;
    color: #ff0000ff;
    text-align: left;
    display: block;
    margin-top: 3px;
  }

  .submit-btn {
    background: linear-gradient(45deg, #00c6ff, #0072ff);
    color: #fff;
    border: none;
    padding: 12px 20px;
    margin-top: 20px;
    width: 100%;
    font-size: 18px;
    font-weight: bold;
    border-radius: 25px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0, 114, 255, 0.4);
    transition: 0.3s;
  }

  .submit-btn:hover {
    background: linear-gradient(45deg, #00ff87, #60efff);
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0, 200, 255, 0.5);
  }

  @media (max-width: 768px) {
    .main {
      width: 90%;
      padding: 25px;
    }
  }
</style>

<script>
  function validateContactForm() {
    let name = document.getElementById("fullname").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("contactno").value.trim();
    let msg = document.getElementById("message").value.trim();
    let valid = true;

    document.getElementById("nameError").innerHTML = "";
    document.getElementById("emailError").innerHTML = "";
    document.getElementById("phoneError").innerHTML = "";
    document.getElementById("msgError").innerHTML = "";

    // if (name === "") {
    //   document.getElementById("nameError").innerHTML = "* Name is required *";
    //   valid = false;
    // }
    if (email === "" || !email.includes("@")) {
      document.getElementById("emailError").innerHTML = "* Enter valid email *";
      valid = false;
    }
    if (phone === "" || isNaN(phone) || phone.length < 10) {
      document.getElementById("phoneError").innerHTML = "* Enter valid phone number *";
      valid = false;
    }
    if (msg === "") {
      document.getElementById("msgError").innerHTML = "* Please enter a message *";
      valid = false;
    }

    return valid;
  }
</script>


	<?php include('includes/footer.php');?>
	<script src="js/jquery-2.2.3.min.js"></script>
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
	<script src="js/fixed-nav.js"></script>
	<script src="js/SmoothScroll.min.js"></script>
	<!-- move-top -->
	<script src="js/move-top.js"></script>
	<!-- easing -->
	<script src="js/easing.js"></script>
	<!--  necessary snippets for few javascript files -->
	<script src="js/medic.js"></script>
	<script src="js/bootstrap.js"></script>
</body>

</html>