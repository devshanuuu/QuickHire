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

$stmt = $conn->prepare("SELECT * FROM profile WHERE user_id = ?");
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
        $file_name = time() . '_' . basename($FILES['resume']['name']);
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile - QuickHire</title>
    <link rel="stylesheet" href="profile.css"> <!-- Create this for styling -->
</head>
<body>
    <header>
        <div class="logo">QuickHire</div>
        <nav>
            <ul>
                <li><a href="update_profile.php">Update Profile</a></li>
                <li><a href="#">Upload Resume</a></li>
                <li><a href="#">Applied Jobs</a></li>
                <li><a href="logout.php">Logout</a></li>
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

