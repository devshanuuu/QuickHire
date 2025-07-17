


<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>

    <p style="color: green; font-weight: bold;">Job added successfully!</p>
    
<?php endif; ?>



<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset="UTF-8">
    <title>Add New Job</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="add-job-page">

    <h2>Add a New Job</h2>

    <form method = "POST" action = "process_add_job.php">
        <label>Job Title</label>
        <input type = "text" name = "job_title" required>

        <label>Job Description</label>
        <textarea name = "job_description" rows = "4" required></textarea>

        <label>Location</label>
        <input type = "text" name = "location" required>

        <label>Salary</label>
        <input type = "text" name = "salary">

        <label>Company</label>
        <input type = "text" name = "company">

        <label for="job_type">Job Type</label>
        <select name="job_type" id="job_type" class="job-select" required>
        <option value="">-- Select --</option>
        <option value="On-site">On-site</option>
        <option value="Remote">Remote</option>
        <option value="Hybrid">Hybrid</option>
        </select>

        <button type = "submit" name = "submit">Add Job</button>
    </form>
    
</body>
</html>    