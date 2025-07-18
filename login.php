<?php 
session_start();

require_once 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Prepare a SQL statement to look up this email in the users table
    $stmt = $conn->prepare("SELECT id, name, password, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if we found a user with this email
    if($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $hashed_password, $user_type);
        $stmt->fetch();

        // Check if the password matches in database
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['name'] = $name;
            $_SESSION['user_type'] = $user_type;

            if ($user_type == 'professional') {
                header("Location: jobs_list.php");
            } else {
                header("Location: add_job.php");
            }
            exit();
        } else {
            // The password is not correct - show error message
            $error = "Incorrect password. Please try again.";
        }
    } else {
        // Email not found in Database
        $error = "Email not registered with us. Please signup first";
    }

    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login - QuickHire</title>
    <link rel="stylesheet" href="forms.css">
</head>
<body>
    <div class="container">
        <h2>Welcome back to QuickHire</h2>
        <!-- Show the error message, if there is one -->
        <?php if (!empty($error)) echo "<p>$error</p>"; ?>
        <!-- Login form: take email and password -->
        <form method="post" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>New user? <a href="signup.php">Sign up here</a></p>
    </div>
</body>
</html>
