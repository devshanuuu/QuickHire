<?php 

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $user_type = $_POST["user_type"];

    // Step 1: Check if email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if (!$check_stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $error = "This email is already registered. Please use a different one.";
    } else {
        // Step 2: Email is unique, proceed with insert
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $user_type);
        
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['name'] = $name;
            $_SESSION['user_type'] = $user_type;

            if ($user_type == 'professional') {
                header("Location: professional_dashboard.php");
            } else {
                header("Location: add_job.php");
            }
            exit();
        } else {
            $error = "Signup failed due to a server error.";
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Signup - QuickHire</title>
    <link rel="stylesheet" href="forms.css">
</head>
<body>
    <div class="container">
        <h2>Your Dream Job. Just One Click Away.</h2>
        <?php if (!empty($error)) echo "<p>$error</p>"; ?>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="user_type" required>
                <option value="">Select Account Type</option>
                <option value="professional">Professional</option>
                <option value="company">Company</option>
            </select>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>

