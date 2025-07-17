<?php
// index.php
require_once 'db.php'; 

// Fetch latest jobs (limit to 5 for homepage)
$sql = "SELECT * FROM jobs ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Job Portal | Homepage</title>
  <link rel="stylesheet" href="homestyle.css">
</head>
<body>

  <div class="container">
    <h1>Latest Job Listings</h1>

    <?php if ($result->num_rows > 0): ?>
      <?php while ($job = $result->fetch_assoc()): ?>
        <div class="job-card">
          <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
          <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
          <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
          <p><strong>Type:</strong> <?php echo htmlspecialchars($job['job_type']); ?></p>
          <a href="jobs/job_details.php?id=<?php echo $job['id']; ?>">View Details</a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No jobs posted yet.</p>
    <?php endif; ?>

    <a href="jobs/job_lists.php" class="view-all-jobs-btn">View All Jobs</a>
  </div>

</body>
</html>
