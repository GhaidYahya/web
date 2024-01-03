<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}


require_once 'db.inc';
$pdo = db_connect();

// Get the team ID 
if (isset($_GET["team_id"])) {
  $teamId = $_GET["team_id"];
} else {
  header("Location: dashboard.php");
  exit();
}


if ($pdo) {
  // Retrieve the team information
  $stmt = $pdo->prepare("SELECT * FROM teams WHERE team_id = ?");
  $stmt->execute([$teamId]);

  if ($stmt->rowCount() > 0) {
    $team = $stmt->fetch(PDO::FETCH_ASSOC);
  } else {
    // Team not found, redirect to dashboard
    header("Location: dashboard.php");
    exit();
  }
} else {
  // Database connection error, redirect to dashboard
  header("Location: dashboard.php");
  exit();
}

// Handle form submission for updating team information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $teamName = $_POST["team_name"];
  $skillLevel = $_POST["skill_level"];
  $gameDay = $_POST["game_day"];

  // Update the team information in the database
  $updateStmt = $pdo->prepare("UPDATE teams SET team_name = ?, skill_level = ?, game_day = ? WHERE team_id = ?");
  $updateStmt->execute([$teamName, $skillLevel, $gameDay, $teamId]);

  // Redirect to the team details page after successful update
  header("Location: teamDetails.php?team_id=$teamId");
  exit();
}

// Handle team deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_team'])) {
  $deleteStmt = $pdo->prepare("DELETE FROM teams WHERE team_id = ?");
  $deleteStmt->execute([$teamId]);

  // Redirect to dashboard after deletion
  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="editstyle.css">
  <title>Edit Team</title>
</head>
<body>
  <header>
    <img src="fbzuLOGO.png" alt="FBZU Logo" height="180" width="230">
    <h2>Edit Team</h2>
  </header>

  <main>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?team_id=<?php echo $teamId; ?>" method="POST">
      <table>
        <tr>
          <td><label for="team_name">Team Name:</label></td>
          <td><input type="text" name="team_name" value="<?php echo $team['team_name']; ?>" required></td>
        </tr>
        <tr>
          <td><label for="skill_level">Skill Level:</label></td>
          <td><input type="number" name="skill_level" min="1" max="5" value="<?php echo $team['skill_level']; ?>" required></td>
        </tr>
        <tr>
          <td><label for="game_day">Game Day:</label></td>
          <td><input type="text" name="game_day" value="<?php echo $team['game_day']; ?>" required></td>
        </tr>
      </table>
      <br>
      <input type="submit" value="Update Team">
    </form>

    <p><a href="teamDetails.php?team_id=<?php echo $teamId; ?>">Back to Team Details</a></p>

    <p>
      <a href="#" onclick="confirmDelete()">Delete Team</a>
      <script>
        function confirmDelete() {
          if (confirm('Are you sure you want to delete this team?')) {
            document.getElementById('deleteForm').submit();
          }
        }
      </script>
    </p>
    <form id="deleteForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?team_id=<?php echo $teamId; ?>" method="POST">
      <input type="hidden" name="team_id" value="<?php echo $teamId; ?>">
      <input type="hidden" name="delete_team" value="1">
    </form>
  </main>

  <footer>
    <p>&copy; <?php echo date('Y'); ?> Ghaid's Kickball League - final project. All rights reserved.</p>
  </footer>
</body>
</html>

