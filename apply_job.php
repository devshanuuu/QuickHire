<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get raw JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['job_id'])) {
    echo json_encode(["success" => false, "message" => "Missing job ID"]);
    exit;
}

$job_id = $data['job_id'];

// Optional: Check if already applied
$check = $conn->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
$check->bind_param("ii", $user_id, $job_id);
$check->execute();
$result = $check->get_result();
if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Already applied"]);
    exit;
}

// Insert application
$stmt = $conn->prepare("INSERT INTO applications (user_id, job_id, applied_at) VALUES (?, ?, NOW())");
$stmt->bind_param("ii", $user_id, $job_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Database error"]);
}
?>
