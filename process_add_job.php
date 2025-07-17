<?php

// Connect to the database using the db.php
 require_once 'db.php';

// Check if form was submitted using POST

 if($_SERVER["REQUEST_METHOD"] == "POST"){

    $title = $_POST['job_title'];
    $description = $_POST['job_description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $company = $_POST['company'];
    $job_type = $_POST['job_type'];


 

// Check if required fields are not empty

if(!empty($title) && !empty($description) && !empty($location)) {

    // SQL query to insert data
    $stmt = $conn->prepare("INSERT INTO jobs (job_title, job_description, location, salary, company, job_type) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
    die("Prepare failed: " . $conn->error);  // 👈 shows the actual error
}
  
    // Bind the actual form values to the SQL placeholders
    // "sssss" means all 5 values are strings
    $stmt->bind_param("ssssss", $title, $description, $location, $salary, $company, $job_type);

    // Try executing the query
    if($stmt->execute()) {
        
        //If success redirect to add_job.php with a success flag in url
        header("Location: add_job.php?success=1");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
   } else {
    
    // If required fields are missing
    echo "please fill all required fields."; 
   }
}

?>