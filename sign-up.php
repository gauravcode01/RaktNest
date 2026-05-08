    <?php   
    session_start();
    error_reporting(0);
    include('includes/config.php');

    $errors = []; // server-side errors array

    if(isset($_POST['submit']))
    {
        $usertype = trim($_POST['usertype']); 
        $fullname=trim($_POST['fullname']);
        $mobile=trim($_POST['mobileno']);
        $email=trim($_POST['emailid']);
        $age=trim($_POST['age']);
        $gender=trim($_POST['gender']);
        $blodgroup=trim($_POST['bloodgroup']);
        $address=trim($_POST['address']);
        $message=trim($_POST['message']);
        $weight=trim($_POST['weight']);
        $height=trim($_POST['height']);
        $blood_pressure=trim($_POST['blood_pressure']);
        $last_donation_date=trim($_POST['last_donation_date']);
        $chronic_diseases=trim($_POST['chronic_diseases']);
        $medications=trim($_POST['medications']);
        $lifestyle=trim($_POST['lifestyle']);
        $allergies=trim($_POST['allergies']);
        $vaccination_status=trim($_POST['vaccination_status']);
        $password=trim($_POST['password']);
        $confirmpassword=trim($_POST['confirmpassword']);
        $status=1;

        // --- VALIDATION ---
        if($usertype == '') $errors['usertype'] = "Please select User Type.";
        if($fullname == '') $errors['fullname'] = "Full Name is required.";
        elseif(!preg_match("/^[a-zA-Z\s]+$/", $fullname)) $errors['fullname'] = "Full Name should only contain letters and spaces.";
        if($mobile == '') $errors['mobileno'] = "Mobile Number is required.";
        elseif(!preg_match("/^\d{10}$/", $mobile)) $errors['mobileno'] = "Mobile Number must be exactly 10 digits.";
        if($email == '') $errors['emailid'] = "Email is required.";
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['emailid'] = "Please enter a valid email address.";
        if($age == '') $errors['age'] = "Age is required.";
        if($gender == '') $errors['gender'] = "Please select Gender.";
        if($blodgroup == '') $errors['bloodgroup'] = "Please select Blood Group.";
        if($address == '') $errors['address'] = "Address is required.";
           if($password == '') {
            $errors['password'] = "Password is required.";
        }
        elseif(strlen($password) < 6) {
            $errors['password'] = "Password must be at least 6 characters.";
        }

        if($confirmpassword == '') {
            $errors['confirmpassword'] = "Confirm Password is required.";
        }
        elseif($password !== $confirmpassword) {
            $errors['confirmpassword'] = "Passwords do not match.";
        }

        // --- INSERT ---
        if(count($errors) === 0){
            $table = ($usertype == "Donor") ? "tblblooddonars" : "tblreceivers";

            // Check both email and mobile number
            $ret="SELECT EmailId, MobileNumber FROM $table WHERE EmailId=:email OR MobileNumber=:mobile";
            $query= $dbh -> prepare($ret);
            $query-> bindParam(':email', $email, PDO::PARAM_STR);
            $query-> bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $query-> execute();

            if($query -> rowCount() == 0)
            {
                $password=md5($password);
                $sql="INSERT INTO $table
                (FullName,MobileNumber,EmailId,Age,Gender,BloodGroup,Address,Message,Weight,Height,Blood_Pressure,Last_Donation_Date,Chronic_Diseases,Medications,Lifestyle,Allergies,Vaccination_Status,status,Password) 
                VALUES(:fullname,:mobile,:email,:age,:gender,:blodgroup,:address,:message,:weight,:height,:blood_pressure,:last_donation_date,:chronic_diseases,:medications,:lifestyle,:allergies,:vaccination_status,:status,:password)";
                
                $query = $dbh->prepare($sql);
                $query->bindParam(':fullname',$fullname,PDO::PARAM_STR);
                $query->bindParam(':mobile',$mobile,PDO::PARAM_STR);
                $query->bindParam(':email',$email,PDO::PARAM_STR);
                $query->bindParam(':age',$age,PDO::PARAM_STR);
                $query->bindParam(':gender',$gender,PDO::PARAM_STR);
                $query->bindParam(':blodgroup',$blodgroup,PDO::PARAM_STR);
                $query->bindParam(':address',$address,PDO::PARAM_STR);
                $query->bindParam(':message',$message,PDO::PARAM_STR);
                $query->bindParam(':weight',$weight,PDO::PARAM_STR);
                $query->bindParam(':height',$height,PDO::PARAM_STR);
                $query->bindParam(':blood_pressure',$blood_pressure,PDO::PARAM_STR);
                $query->bindParam(':last_donation_date',$last_donation_date,PDO::PARAM_STR);
                $query->bindParam(':chronic_diseases',$chronic_diseases,PDO::PARAM_STR);
                $query->bindParam(':medications',$medications,PDO::PARAM_STR);
                $query->bindParam(':lifestyle',$lifestyle,PDO::PARAM_STR);
                $query->bindParam(':allergies',$allergies,PDO::PARAM_STR);
                $query->bindParam(':vaccination_status',$vaccination_status,PDO::PARAM_STR);
                $query->bindParam(':status',$status,PDO::PARAM_STR);
                $query->bindParam(':password',$password,PDO::PARAM_STR);
                $query->execute();
                $lastInsertId = $dbh->lastInsertId();

                if($lastInsertId)
                {
                    echo "<script>
                        setTimeout(function(){ window.location.href='login.php'; }, 2000);
                    </script>";
                    $successMsg = "You have signed up successfully as $usertype. Redirecting to login...";
                }
                else { $errors['general'] = "Something went wrong. Please try again."; }
            } else {
                $existing = $query->fetch(PDO::FETCH_ASSOC);
                if($existing['EmailId'] == $email && $existing['MobileNumber'] == $mobile){
                    $errors['emailid'] = "This Email and Mobile Number already exist. Redirecting to login...";
                    $errors['mobileno'] = "This Email and Mobile Number already exist. Redirecting to login...";
                } elseif($existing['EmailId'] == $email){
                    $errors['emailid'] = "This Email-id already exists. Redirecting to login...";
                } else {
                    $errors['mobileno'] = "This Mobile Number already exists. Redirecting to login...";
                }

    $_SESSION['signup_msg'] = "This Email or Mobile Number already exists. Please login to continue.";
    header("Location: login.php");
    exit;


            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="zxx">
    <head>
        <title>RakhtNest | Signup</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/fontawesome-all.css">
        <style>
            body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; background:#fff; }
            .card { border-radius: 15px; background-color: #fff; box-shadow: 0 0 25px rgba(255, 0, 0, 0.1), 0 8px 20px rgba(0,0,0,0.1); padding: 30px 20px; transition: 0.2s; }
            .card:hover { transform: translateY(-3px); box-shadow: 0 0 30px rgba(255, 0, 0, 0.2), 0 10px 25px rgba(0,0,0,0.1); }
            .error-message { color: red; font-size: 0.9em; margin-top: 5px; }
            .success-message { color: green; font-size: 0.9em; margin-bottom: 5px; }
        </style>
    </head>

    <body>
    <?php include('includes/header.php');?>

    <div class="inner-banner-w3ls"><div class="container"></div></div>
    <div class="breadcrumb-agile">
        <div aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Signup</li>
            </ol>
        </div>
    </div>

    <section class="about py-5">
        <div class="container py-xl-5 py-lg-3">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card p-4">
                        <h4 class="text-center mb-4" style="color:red;"><b>Register Now</b></h4>

                        <?php if(isset($errors['general'])): ?>
                            <div class="alert alert-danger text-center"><?php echo $errors['general']; ?></div>
                        <?php endif; ?>
                        <?php if(isset($successMsg)): ?>
                            <div class="success-message text-center"><?php echo $successMsg; ?></div>
                        <?php endif; ?>

                        <form method="post" name="signup" novalidate>

<div class="form-group mb-3">
    <label><b>User Type</b></label>
    <select name="usertype" class="form-control" id="usertype">
        <option value="">Select</option>
        <option value="Donor" <?php if(($usertype ?? '')=="Donor") echo "selected"; ?>>Donor</option>
        <option value="Receiver" <?php if(($usertype ?? '')=="Receiver") echo "selected"; ?>>Receiver</option>
    </select>
    <?php if(isset($errors['usertype'])) echo "<div class='error-message'>{$errors['usertype']}</div>"; ?>
</div>

                            <div class="form-group mb-3">
                                <label><b>Full Name</b></label>
                                <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($fullname ?? ''); ?>">
                                <?php if(isset($errors['fullname'])) echo "<div class='error-message'>{$errors['fullname']}</div>"; ?>
                            </div>

                            <div class="form-group mb-3">
                                <label><b>Mobile Number</b></label>
                                <input type="text" name="mobileno" maxlength="10" class="form-control" value="<?php echo htmlspecialchars($mobile ?? ''); ?>">
                                <?php if(isset($errors['mobileno'])) echo "<div class='error-message'>{$errors['mobileno']}</div>"; ?>
                            </div>

                            <div class="form-group mb-3">
                                <label><b>Email Id</b></label>
                                <input type="email" name="emailid" class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                                <?php if(isset($errors['emailid'])) echo "<div class='error-message'>{$errors['emailid']}</div>"; ?>
                            </div>

                            <div class="form-group mb-3">
                                <label><b>Age</b></label>
                                <input type="number" name="age" class="form-control" value="<?php echo htmlspecialchars($age ?? ''); ?>">
                                <?php if(isset($errors['age'])) echo "<div class='error-message'>{$errors['age']}</div>"; ?>
                            </div>

                            <div class="form-group mb-3">
                                <label><b>Gender</b></label>
                                <select name="gender" class="form-control">
                                    <option value="">Select</option>
                                    <option <?php if(($gender ?? '')=="Male") echo "selected"; ?>>Male</option>
                                    <option <?php if(($gender ?? '')=="Female") echo "selected"; ?>>Female</option>
                                </select>
                                <?php if(isset($errors['gender'])) echo "<div class='error-message'>{$errors['gender']}</div>"; ?>
                            </div>

                            <div class="form-group mb-3">
                                <label><b>Blood Group</b></label>
                                <select name="bloodgroup" class="form-control">
                                    <option value="">Select</option>
                                    <?php 
                                    $sql = "SELECT * from tblbloodgroup";
                                    $query = $dbh -> prepare($sql);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) { ?>  
                                            <option value="<?php echo htmlentities($result->BloodGroup);?>" 
                                                <?php if(($blodgroup ?? '')==$result->BloodGroup) echo "selected"; ?>>
                                                <?php echo htmlentities($result->BloodGroup);?>
                                            </option>
                                    <?php }} ?>
                                </select>
                                <?php if(isset($errors['bloodgroup'])) echo "<div class='error-message'>{$errors['bloodgroup']}</div>"; ?>
                            </div>

                            <div class="form-group mb-3">
                                <label><b>Address</b></label>
                                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($address ?? ''); ?>">
                                <?php if(isset($errors['address'])) echo "<div class='error-message'>{$errors['address']}</div>"; ?>
                            </div>

                            <h5 class="text-danger mt-4"><b>Medical Information</b></h5>
                            <div class="form-group mb-3">
                                <label>Weight (kg)</label>
                                <input type="text" name="weight" class="form-control" value="<?php echo htmlspecialchars($weight ?? ''); ?>">
                            </div>
<div class="form-group mb-3" id="lastDonationWrapper">
    <label>Last Donation Date</label>
    <input type="date" name="last_donation_date" class="form-control" 
    value="<?php echo htmlspecialchars($last_donation_date ?? ''); ?>">
</div>


                            <h5 class="text-danger mt-4"><b>Medical History</b></h5>
                            <div class="form-group mb-3">
                                <label>Chronic Diseases</label>
                                <input type="text" name="chronic_diseases" class="form-control" value="<?php echo htmlspecialchars($chronic_diseases ?? ''); ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label>Medications</label>
                                <input type="text" name="medications" class="form-control" value="<?php echo htmlspecialchars($medications ?? ''); ?>">
                            </div>

                            <div class="form-group mb-3">
                                <label>Lifestyle</label>
                                <select name="lifestyle" class="form-control">
                                    <option value="">Select</option>
                                    <option <?php if(($lifestyle ?? '')=="Alcoholic") echo "selected"; ?>>Alcoholic</option>
                                    <option <?php if(($lifestyle ?? '')=="Smoker") echo "selected"; ?>>Smoker</option>
                                    <option <?php if(($lifestyle ?? '')=="Both") echo "selected"; ?>>Both</option>
                                    <option <?php if(($lifestyle ?? '')=="None") echo "selected"; ?>>None</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label><b>Password</b></label>
                                <input type="password" name="password" class="form-control">
                                <?php if(isset($errors['password'])) echo "<div class='error-message'>{$errors['password']}</div>"; ?>
                            </div>

                            <div class="form-group mb-4">
                                <label><b>Confirm Password</b></label>
                                <input type="password" name="confirmpassword" class="form-control">
                                <?php if(isset($errors['confirmpassword'])) echo "<div class='error-message'>{$errors['confirmpassword']}</div>"; ?>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger btn-block" name="submit">Register</button>
                            </div>

                            <p class="text-center mt-3 mb-0" style="color:#000">
                                Already Registered? <a href="login.php">Login now</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
    // Show/Hide Last Donation Date field
    function toggleLastDonation() {
        var userType = document.getElementById("usertype").value;
        var lastDonationField = document.getElementById("lastDonationWrapper");

        if (userType === "Receiver") {
            lastDonationField.style.display = "none";
        } else {
            lastDonationField.style.display = "block";
        }
    }

    // Run on dropdown change
    document.getElementById("usertype").addEventListener("change", toggleLastDonation);

    // Run on page load
    window.onload = toggleLastDonation;
</script>

    </section>
    <?php include('includes/footer.php');?>
    <script src="js/bootstrap.js"></script>
    </body>
    </html>
