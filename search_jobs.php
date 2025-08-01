<?php 
require_once 'db.php';

$role = isset($_GET['role']) ? trim($_GET['role']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';

$sql = "SELECT * FROM jobs WHERE 1";
$params = [];
$types = "";

if(!empty($role)) {
    $sql .= " AND job_title LIKE ?";
    $params[] = "%$role%";
    $types .= "s";
}
if(!empty($location)) {
    $sql .= " AND location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params); // spread operator for dynamic params
}

$stmt->execute();
$result = $stmt->get_result();
$jobs = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - QuickHire</title>
    <link rel="stylesheet" href="search_style.css"> <!-- Adjust this to match your theme -->
</head>
<body>
    <header>
        <h1>Search Results</h1>
    </header>

    <section class="search-summary">
        <p>Showing results for-
            <strong>Role:</strong> <?= htmlspecialchars($role) ?: 'Any' ?> |
            <strong>Location:</strong> <?= htmlspecialchars($location) ?: 'Any' ?>
        </p>
        <a href="professional_dashboard.php" class="back-link">← Back to Home</a>
    </section>

    <section class="job-results">
        <?php if (count($jobs) > 0): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <h3><?= htmlspecialchars($job['job_title']) ?></h3>
                    <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                    <?= nl2br(htmlspecialchars($job['job_description'])) ?>
                    <p><a href="job_details.php?id=<?= $job['id'] ?>" class="apply-btn">View Details</a></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">No jobs found matching your criteria.</p>
        <?php endif; ?>
    </section>
</body>
</html>