<?php
session_start();


// Include the database connection file
require_once 'db.inc';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate form data and perform registration
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  // Perform validation checks
  $errors = array();
  if (empty($username)) {
    $errors[] = "Username is required.";
  }
  if (empty($email)) {
    $errors[] = "Email is required.";
  }
  if (empty($password)) {
    $errors[] = "Password is required.";
  } elseif ($password !== $confirm_password) {
    $errors[] = "Passwords do not match.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    // Check if user already exists
    $pdo = db_connect();

    if ($pdo) {
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
      $stmt->bindValue(1, $email, PDO::PARAM_STR);
      $stmt->execute();
      if ($stmt->rowCount() > 0) {
        $errors[] = "User with this email already exists.";
      } else {
        // Insert user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $email, PDO::PARAM_STR);
        $stmt->bindValue(3, $password, PDO::PARAM_STR);
        $stmt->execute();

        // Redirect to login page after successful registration
        $_SESSION["success"] = "Registration successful. Please log in.";
        header("Location: login.php");
        exit();
      }
    } else {
      $errors[] = "Failed to connect to the database.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="rigStyle.css">

  <title>User Registration</title>
</head>
<body>
  <header>
  <header>
        <center>
            <img src="fbzuLOGO.png" alt="Football Logo" height="180" width="230">
            <h1>Register</h1>
        </center>
  </header>

  <main>
    <h2><strong>Welcome!</strong></h2>

    <?php
    // Display errors, if any
    if (!empty($errors)) {
      echo "<ul>";
      foreach ($errors as $error) {
        echo "<li>$error</li>";
      }
      echo "</ul>";
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <table>
        <tr>
          <td colspan="2" style="text-align: center;"><h3>Register</h3></td>
        </tr>
        <tr>
          <td>Username:</td>
          <td><input type="text" name="username" required></td>
        </tr>
        <tr>
          <td>Email:</td>
          <td><input type="email" name="email" required></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input type="password" name="password" minlength="8" required></td>
        </tr>
        <tr>
          <td>Confirm Password:</td>
          <td><input type="password" name="confirm_password" minlength="8" required></td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center;"><input type="submit" value="Register" ></td>
        </tr>
      </table>
    </form>
  </main>

  <footer>
  <p>&copy; 2023 Ghaid's Kickball League - final project. All rights reserved.</p>
  </footer>
</body>
</html>

