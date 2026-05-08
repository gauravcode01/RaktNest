<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:index.php');
}
else{
if(isset($_POST['update']))
{
    $bloodgroup = $_POST['bloodgroup'];
    $id = intval($_GET['id']);

    $sql="update tblbloodgroup set BloodGroup=:bloodgroup where id=:id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bloodgroup',$bloodgroup,PDO::PARAM_STR);
    $query->bindParam(':id',$id,PDO::PARAM_STR);
    $query->execute();

    // Redirect with success message
    echo "<script>alert('Blood Group Updated Successfully');</script>";
    echo "<script>window.location.href='manage-bloodgroup.php';</script>";
    exit;
}

$id=intval($_GET['id']);
$sql = "SELECT * from tblbloodgroup where id=:id";
$query = $dbh -> prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query->execute();
$result=$query->fetch(PDO::FETCH_OBJ);
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <title>RaktNest | Edit Blood Group</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include('includes/header.php');?>

<div class="ts-main-content">
<?php include('includes/leftbar.php');?>
<div class="content-wrapper">
    <div class="container-fluid">
        <h2 class="page-title">Edit Blood Group</h2>
        <div class="panel panel-default">
            <div class="panel-heading">Update Blood Group</div>
            <div class="panel-body">
                <form method="post">
                    <div class="form-group">
                        <label>Blood Group</label>
                        <input type="text" name="bloodgroup" class="form-control" value="<?php echo htmlentities($result->BloodGroup);?>" required>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                    <a href="manage-bloodgroup.php" class="btn btn-default">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
