<?php
  
  // Connect to DB
  require_once 'db.php';
  $sql = "SELECT * FROM jobs ORDER BY id DESC";
  $result = $conn->query($sql);
  ?>

  <!DOCTYPE html>
  <html>
<head>
    <title>Job Listings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="job-list-page">

<h2>Available Jobs</h2>

<?php if ($result->num_rows > 0): ?> <!-- check if there are any rows in the result -->
    <?php while($row = $result->fetch_assoc()): ?> <!-- Loops through each row of the result and stores it in $row -->
    <div class = "job-card">
        <h3>
          <a href="job_details.php?id=<?= $row['id'] ?>">
            <?= htmlspecialchars($row['job_title']) ?>
          </a>
        </h3>
        <div class = "job-details">
            <span class ="label">Company:</span> <?= htmlspecialchars($row['company']) ?>
        </div>
        <div class = "job-details">
            <span class ="label">Location:</span> <?= htmlspecialchars($row['location']) ?>
        </div>
        <div class = "job-details">
            <span class ="label">Salary:</span> <?= htmlspecialchars($row['salary']) ?>
        </div>
        
    </div>
<?php endwhile; ?>
<?php else: ?>
    <p class ="no-jobs">No jobs found</p>
<?php endif; ?>

</body>
</html>

