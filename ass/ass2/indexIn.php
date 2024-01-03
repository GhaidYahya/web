<?php
require_once 'db.inc';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $pdo = db_connect();

    if ($pdo !== false) {
        // Check if the email and password match in the database
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE E_mail = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
           
            session_start();

            
            $_SESSION['email'] = $email;

            
            header('Location: indexafterIn.php');
            exit();
        } else {
            $error = "This account doesn't exist or the password is incorrect. Please re-check your entries.";
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
    <title>Sign In</title>
</head>
<body>
    <h1>Sign In</h1>
    <?php if (!empty($error)) { ?>
        <p>Error: <?php echo $error; ?></p>
    <?php } ?>
    <form method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br>

        <input type="submit" value="Sign In">
    </form>
</body>
</html>
