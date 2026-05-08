<?php
error_reporting(0);
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
	<title>Blood Bank Donar Management System | About Us </title>
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
				<li class="breadcrumb-item active" aria-current="page">About Us</li>
			</ol>
		</div>
	</div>
	<!-- //page details -->

	<!-- about -->
	<section class="about py-5">
        <div class="container py-xl-5 py-lg-3">
            <div class="w3ls-titles text-center mb-md-5 mb-4">
                <h3 class="title">About Raktnest</h3>
                <span>
                    <i class="fas fa-user-md"></i>
                </span>
            </div>
            <p class="aboutpara text-center mx-auto" style="color:black;">
                <strong>Raktnest</strong> is a community-driven blood donation bank dedicated to saving lives by bridging the gap between blood donors and patients in need. Operating as a compassionate and accessible resource, Raktnest makes it easy for volunteers to donate blood and for hospitals and emergency services to find safe, reliable supplies when they matter most.
            </p>
            <br>
            <h4 class="text-center">Our Story</h4>
            <p class="aboutpara text-center mx-auto" style="color:black;">
                Raktnest began with a simple idea: every drop of blood has the power to create hope. From humble beginnings, we have grown into an essential network supporting patients, families, and healthcare providers during times of crisis and routine care alike. With every new donor and every fulfilled request, we strengthen our commitment to community health and service.
            </p>
            <br>
            <h4 class="text-center">What We Do</h4>
            <ul class="aboutpara mx-auto" style="max-width:700px;">
                <li><strong>Donation Management:</strong> We guide donors through safe, transparent processes, ensuring each donation meets the highest medical standards.</li>
                <li><strong>24/7 Support:</strong> Raktnest operates around the clock, prepared to help when emergencies strike and to support ongoing needs for blood supplies.</li>
                <li><strong>Awareness & Outreach:</strong> Through educational campaigns and collaboration with local organizations, we help raise awareness about blood donation and encourage voluntary involvement.</li>
                <li><strong>Technology-Driven Access:</strong> Our online portal allows easy registration, donation scheduling, and real-time updates on blood availability for hospitals and patients.</li>
            </ul>
            <br>
            <h4 class="text-center">Our Mission</h4>
            <p class="aboutpara text-center mx-auto" style="color:black;">
                Raktnest’s mission is to make safe, voluntary blood donation a norm in every community, ensuring that no individual struggles to find life-saving blood when it’s needed most.
            </p>
            <br>
            <h4 class="text-center">Our Values</h4>
            <ul class="aboutpara mx-auto" style="max-width:700px;">
                <li><strong>Safety:</strong> We prioritize safety for donors and recipients at every step of the process.</li>
                <li><strong>Compassion:</strong> Each interaction reflects our commitment to treating everyone with care and respect.</li>
                <li><strong>Transparency:</strong> We believe in clear, honest communication about donations, requests, and fulfillment.</li>
                <li><strong>Community:</strong> Raktnest thrives on volunteer spirit and collective responsibility.</li>
            </ul>
            <br>
            <b><h4 class="text-center">Join Us</h4></b>
            <p class="aboutpara text-center mx-auto"style="color:black;">
                Whether you want to become a donor, need support, or wish to help spread the word, Raktnest welcomes everyone to join our life-saving mission. Every donor and every contribution creates hope for someone in need.
            </p>
        </div>
    </section>
    <!-- //about -->



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
	<!-- //fixed navigation -->

	<!-- smooth scrolling -->
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