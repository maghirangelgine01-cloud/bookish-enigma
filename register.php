<?php
include 'connect.php'; 
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $confirm = trim($_POST['confirm_password']);

  
  if ($password !== $confirm) {
    $error = "Passwords do not match!";
  } else {
    
    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      $error = "Username already exists!";
    } else {
     
      $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'student')");
      $stmt->bind_param("sss", $username, $email, $password);

      if ($stmt->execute()) {
        $success = "Registration successful! You can now <a href='web.php'>login</a>.";
      } else {
        $error = "Something went wrong. Please try again.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <link rel="stylesheet" href="register.css">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration</title>
</head>
<body>
  <div class="container">
    <div class="register-box">
      <h2>Student Registration</h2>
      <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required />
        <input type="text" name="username" placeholder="Enter Username" required />
        <input type="password" name="password" placeholder="Enter Password" required />
        <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        <button type="submit">Register</button>
      </form>

      <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
      <?php endif; ?>

      <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
      <?php endif; ?>

      <p>Already have an account? <a href="web.php">Login here</a></p>
    </div>

    <div class="logo">
      <img src="pic.png" alt="Logo">
    </div>
  </div>
</body>
</html>
