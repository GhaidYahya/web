<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

// Get the user's email from the session
$loggedInEmail = $_SESSION['email'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Process the form data and add the team to the database
  $teamName = $_POST["team_name"];
  $skillLevel = $_POST["skill_level"];
  $gameDay = $_POST["game_day"];

 
  require_once 'db.inc';
  $pdo = db_connect();

  if ($pdo) {
    // Retrieve the user ID based on the email
    $userStmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $userStmt->execute([$loggedInEmail]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
      $userId = $user['user_id'];

      // Insert the team into the database
      $stmt = $pdo->prepare("INSERT INTO teams (team_name, skill_level, game_day, user_id) VALUES (?, ?, ?, ?)");
      $stmt->execute([$teamName, $skillLevel, $gameDay, $userId]);

      // go to the dashboard page after successful team creation
      header("Location: dashboard.php");
      exit();
    } else {
      echo "User not found.";
    }
  } else {
    echo "Failed to connect to the database.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style2.css">
  <title>Create New Team</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <img src="fbzuLOGO.png" alt="FBZULOGO" height="180" width="230">
    <h1>Create New Team</h1>
  </header>

  <main>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <table>
        <tr>
          <td><label for="team_name">Team Name:</label></td>
          <td><input type="text" name="team_name" required></td>
        </tr>
        <tr>
          <td><label for="skill_level">Skill Level:</label></td>
          <td><input type="number" name="skill_level" min="1" max="5" required></td>
        </tr>
        <tr>
          <td><label for="game_day">Game Day:</label></td>
          <td><input type="text" name="game_day" required></td>
        </tr>
      </table>
      <br>
      <input type="submit" value="Create Team">
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
  </main>

  <footer>
  <p>&copy; 2023 Ghaid's Kickball League - final project. All rights reserved.</p>
  </footer>
</body>
</html>
