<?php
include('includes/config.php'); // Database connection

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action == "accept" || $action == "reject") {
        // Delete record from tblbloodrequirer
        $sql = "DELETE FROM tblbloodrequirer WHERE ID = :id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            if ($action == "accept") {
                echo "<script>alert('Request Accepted'); window.location.href='requests-received.php';</script>";
            } else {
                echo "<script>alert('Request Rejected'); window.location.href='requests-received.php';</script>";
            }
        } else {
            echo "<script>alert('No record found or already deleted.'); window.location.href='requests-received.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid action!'); window.location.href='requests-received.php';</script>";
    }
}
?>

<?php include('includes/header.php'); ?>
