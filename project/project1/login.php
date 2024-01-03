<?php
require_once 'db.inc';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $pdo = db_connect();

    if ($pdo !== false) {
        // Check if the email and password match in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            session_start();

            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user['user_id'];

            header('Location: dashboard.php');
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
    <link rel="stylesheet" href="loginstyle.css">
    <title>Sign In</title>
</head>
<body>
    <header>
        <center>
            <img src="fbzuLOGO.png" alt="Football Logo" height="180" width="230">
            <h1>Log In</h1>
        </center>
        
    </header>

    <main>
        <section>
            <form method="post">
                <table>
                    <tr>
                        <td>Email:</td>
                        <td><input type="email" name="email" required></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="password" required></td>
                    </tr>
                </table>
                <button type="submit" class="sign-in-button">
                <img src="sign-in.png" alt="Sign In Icon" height="40" width="30";>
        Sign In
    </button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Ghaid's Kickball League - final project. All rights reserved.</p>
    </footer>
</body>
</html>
