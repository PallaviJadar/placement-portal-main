<?php
session_start();
include 'db_connect.php';  // Include your database connection file

// Check if token is provided
if (isset($_GET['token'])) {
    $reset_token = $_GET['token'];

    // Query the database to find the user with the matching token
    $query = "SELECT * FROM users WHERE reset_token = '$reset_token' AND reset_token_expiry > NOW()";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // If user is found, display password reset form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);  // Hash the new password

            // Update the user's password and reset the token
            $update_query = "UPDATE users SET password = '$new_password', reset_token = NULL, reset_token_expiry = NULL WHERE id = ".$user['id'];
            mysqli_query($conn, $update_query);

            $_SESSION['resetPasswordSuccess'] = "Your password has been reset successfully!";
            header("Location: login-candidates.php");
            exit();
        }
    } else {
        // Token is invalid or expired
        $_SESSION['resetPasswordError'] = "Invalid or expired token!";
        header("Location: forgot-password.php");
        exit();
    }
} else {
    // No token provided
    $_SESSION['resetPasswordError'] = "Invalid request!";
    header("Location: forgot-password.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reset Password</title>
</head>
<body>

  <h2>Reset Your Password</h2>

  <form method="POST">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" id="new_password" required>
    <button type="submit">Submit</button>
  </form>

</body>
</html>
