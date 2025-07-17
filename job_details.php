<?php
require_once 'db.php';

// Get the job ID from URL
$job_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Prepare and run query
$sql = "SELECT * FROM jobs WHERE id = ?"; // Safer to prevent SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id); // Replaces ? with real job ID
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 1):
   $job = $result->fetch_assoc();
else:
    echo "<p>Job not found</p>";
    exit;
endif;
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($job['job_title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="job-details-page">

<div class = "job-card">
     <h2><?= htmlspecialchars($job['job_title']) ?></h2>
  <div class="job-details">
    <span class="label">Company:</span> <?= htmlspecialchars($job['company']) ?>
  </div>
  <div class="job-details">
    <span class="label">Location:</span> <?= htmlspecialchars($job['location']) ?>
  </div>
  <div class="job-details">
    <span class="label">Salary:</span> <?= htmlspecialchars($job['salary']) ?>
  </div>
  <div class="job-details">
    <span class="label">Description:</span><br>
    <?= nl2br(htmlspecialchars($job['job_description'])) ?>
  </div>
</div>

</body>
</html>