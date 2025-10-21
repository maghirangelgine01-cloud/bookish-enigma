<?php
session_start();
include 'connect.php'; 

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE TRIM(username) = ? AND TRIM(password) = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            
            if ($user['role'] === 'admin') {
                header("Location: admin.php");
                exit();
            } elseif ($user['role'] === 'student') {
                header("Location: all.php");
                exit();
            } else {
                $error = "Unknown role in database.";
            }
        } else {
            $error = "Invalid username or password.";
        }

        $stmt->close();
    } else {
        $error = "Please fill in both fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <link rel="stylesheet" href="web.css">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="all.css">
  
</head>
<body>
 <div class="login-container">
  <div class="login-box">
    <h2>Login</h2>
    <form method="POST" autocomplete="on">
      <input type="text" name="username" placeholder="Username" required autocomplete="username" />
      <input type="password" name="password" placeholder="Password" required autocomplete="current-password" />
      <button type="submit">Login</button>
      <p>Don't have an account? <a href="register.php">Register now!</a></p>
    </form>

    <?php if (!empty($error)): ?>
      <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
  </div>

  
  <div class="login-logo">
    <img src="pic.png" alt="Logo">
  </div>
</div>

</body>
</html>
