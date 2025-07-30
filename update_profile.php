<?php 
session_start();

require_once 'db.php';

if(!isset($_SESSION['user_id'])) {
    header('location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$full_name = $age = $qualification = $skills = $resume = '';
$is_update = false;

// Check if a profile already exists

$stmt = $conn->prepare("SELECT * FROM profiles WHERE user_id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $is_update = true;
    $row = $result->fetch_assoc(); // Fetch the profile data as an associative array (column names as keys)
    $full_name = $row['full_name'];
    $age = $row['age'];
    $qualification = $row['qualification'];
    $skills = $row['skills'];
    $resume = $row['resume'];
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $qualification = $_POST['qualification'];
    $skills = $_POST['skills'];

    // Handle Resume upload
    if(isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) { // UPLOAD_ERR_OK is a PHP constant with value 0 meaning success.
        $file_tmp = $_FILES['resume']['tmp_name']; // Temporary file path on the server 
        $file_name = $_FILES['resume']['name']; 
        move_uploaded_file($file_tmp, "uploads/$file_name"); // Move file to 'uploads' directory
        $resume = $file_name; // Save the filename(to be stored in DB)
    }

    
    if ($is_update) {
        // Update profile
        if ($resume) {
            $stmt = $conn->prepare("UPDATE profiles SET full_name=?, age=?, qualification=?, skills=?, resume=? WHERE user_id=?");
            $stmt->bind_param("sisssi", $full_name, $age, $qualification, $skills, $resume, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE profile SET full_name=?, age=?, qualification=?, skills=? WHERE user_id=?");
            $stmt->bind_param("sissi", $full_name, $age, $qualification, $skills, $user_id);
        }
    } else {
        // Create profile
        $stmt = $conn->prepare("INSERT INTO profiles (user_id, full_name, age, qualification, skills, resume, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("isisss", $user_id, $full_name, $age, $qualification, $skills, $resume);
    }

    $stmt->execute();
    header("Location: update_profile.php?success=1");
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile - QuickHire</title>
    <link rel="stylesheet" href="update_profile.css"> <!-- Create this for styling -->
</head>
<body>
    <header>
        <div class="logo">QuickHire</div>
        <nav>
            <ul>
                <li><a href="professional_dashboard.php">Home</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2><?= $is_update ? 'Update Your Profile' : 'Create Your Profile' ?></h2>

        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <label>Full Name:</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($full_name) ?>" required>

            <label>Age:</label>
            <input type="number" name="age" value="<?= htmlspecialchars($age) ?>" required>

            <label>Qualification:</label>
            <select name="qualification" required>
             <option value="">Select Qualification</option>
             <option value="High School" <?php if ($qualification == "High School") echo "selected"; ?>>High School</option>
             <option value="Diploma" <?php if ($qualification == "Diploma") echo "selected"; ?>>Diploma</option>
             <option value="Graduate" <?php if ($qualification == "Graduate") echo "selected"; ?>>Graduate</option>
             <option value="Post Graduate" <?php if ($qualification == "Post Graduate") echo "selected"; ?>>Post Graduate</option>
            </select>

            <label>Skills:</label>
            <input type="text" name="skills" value="<?= htmlspecialchars($skills) ?>" required>

            <label>Resume (PDF):</label>
            <?php if ($resume): ?> <!-- If the user has already uploaded a resume -->
                <p>Current Resume: <a href="uploads/<?= $resume ?>" target="_blank"><?= $resume ?></a></p> <!-- target="_blank" opens the resume in a new tab when clicked -->
            <?php endif; ?>
            <input type="file" name="resume">

            <button type="submit"><?= $is_update ? 'Update Profile' : 'Create Profile' ?></button>
        </form>
    </div>
</body>
</html>

