<?php

require_once 'db.inc';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    
    $pdo = db_connect();

  
    if ($pdo !== false) {
        // Check if the email already exists in the database
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE E_mail = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $error = "This email is already used. Please try a different one.";
        } else {
            // Check if the passwords match
            if ($password === $confirmPassword) {
                // Check if the password is at least 8 characters long
                if (strlen($password) >= 8) {
        
                    $stmt = $pdo->prepare("INSERT INTO Users (E_mail, password) VALUES (:email, :password)");
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);

                   
                    $stmt->execute();

                    
                    header('Location: indexafterIn.php');
                    exit();
                } else {
                    $error = "Password must be at least 8 characters long.";
                }
            } else {
                $error = "Passwords do not match.";
            }
        }
    } else {
        $error = "Failed to connect to the database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h1>Sign Up</h1>
    <?php if (!empty($error)) { ?>
        <p>Error: <?php echo $error; ?></p>
    <?php } ?>
    <form action="signup.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" minlength="8" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" minlength="8" required><br>

        <input type="submit" value="Sign Up">
    </form>
</body>
</html>
