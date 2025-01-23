<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Forgot Password</title>
</head>
<body>

  <h2>Forgot Password</h2>

  <!-- Form to enter email address for password reset -->
  <form method="POST" action="process-forgot-password.php">
    <label for="email">Enter your email address:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Submit</button>
  </form>

</body>
</html>
