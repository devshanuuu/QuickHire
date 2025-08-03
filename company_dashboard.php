<?php
session_start();
require_once 'db.php';

// Redirect if not logged in or company
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    header("location: login.php");
    exit();
}

$company_id = $_SESSION['user_id'];
$company_name = $_SESSION['name'];

// Fetch jobs posted by this company
$stmt = $conn->prepare("SELECT id, job_title, location, created_at FROM jobs WHERE posted_by = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Dashboard - QuickHire</title>
    <link rel="stylesheet" href="dashboardstyle.css"> 
</head>

<body>
    <header>
        <div class="logo">
           <a href="company_dashboard.php" style="text-decoration: none;">QuickHire</a>
        </div>
        <nav>
            <ul>
                <li><a href="add_job.php">Post a job</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
     <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($company_name); ?>!</h1>
        <p>Here's a quick overview of your job postings.</p>

        <a href="post-job.php" class="btn-post-job">+ Post a New Job</a>

        <table class="job-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Location</th>
                    <th>Posted On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="edit-job.php?id=<?php echo $row['id']; ?>">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No jobs posted yet. Start by posting one!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    