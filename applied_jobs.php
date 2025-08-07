<?php 

session_start();
require_once'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'professional') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch applied jobs
$sql = "SELECT jobs.id, jobs.job_title, jobs.salary, jobs.company, jobs.job_type
        FROM jobs
        INNER JOIN applications ON jobs.id = applications.job_id
        WHERE applications.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

