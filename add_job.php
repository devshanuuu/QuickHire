<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset="UTF-8">
    <title>Add New Job</title>
    <style>
        body {font-family: Arial; padding: 20px;}
        input, textarea {display: block; width: 100%; margin: 10px 0; padding: 8px;}
        button {padding: 10px 20px; background: green; color: white; border: none;}
    </style>
</head>
<body>

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

        <button type = "submit" name = "submit">Add Job</button>
    </form>
    
</body>
</html>    