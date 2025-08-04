<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $job_id = intval($_GET['id']); // intval-> built-in phpm function that converts a value into an integer

    $stmt = $conn->prepare("DELETE FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $job_id);

    if ($stmt->execute()) {
        header("Location: company_dashboard.php?deleted=1");
        exit;
    } else {
        echo "Error deleting job.";
    }
}