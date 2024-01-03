<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}


require_once 'db.inc';
$pdo = db_connect();

// Get the team ID from the query parameters
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

    // Retrieve the players in the team
    $playersStmt = $pdo->prepare("SELECT * FROM players WHERE team_id = ?");
    $playersStmt->execute([$teamId]);
    $players = $playersStmt->fetchAll(PDO::FETCH_ASSOC);
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


$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_player'])) {
  // Retrieve the current player count
  $playerCountStmt = $pdo->prepare("SELECT COUNT(*) AS player_count FROM players WHERE team_id = ?");
  $playerCountStmt->execute([$teamId]);
  $playerCountRow = $playerCountStmt->fetch(PDO::FETCH_ASSOC);
  $playerCount = $playerCountRow['player_count'];

  // Check if the maximum player count (9) has been reached
  if ($playerCount >= 9) {
    $message = "Maximum number of players reached, can't add more players!";
  } else {
    $playerName = $_POST["player_name"];

    // Insert the player into the database
    $insertStmt = $pdo->prepare("INSERT INTO players (player_name, team_id) VALUES (?, ?)");
    $insertStmt->execute([$playerName, $teamId]);

    // Refresh the page to show the updated players list
    header("Location: teamDetails.php?team_id=$teamId");
    exit();
  }
}

// Handle team deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_team'])) {
  $deleteStmt = $pdo->prepare("DELETE FROM teams WHERE team_id = ?");
  $deleteStmt->execute([$teamId]);

  // Delete the players associated with the team
  $deletePlayersStmt = $pdo->prepare("DELETE FROM players WHERE team_id = ?");
  $deletePlayersStmt->execute([$teamId]);

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
  <link rel="stylesheet" href="style2.css">
  <title>Team Details</title>
</head>
<body>
  <header>
    <center>
    <img src="fbzuLOGO.png" alt="Football Logo" height="180" width="230">
    <nav>
      <ul>
        <li><a href="createTeam.php">create team</a></li>
        <li><a href="editTeam.php">edit team</a></li>
        <li><a href="editTeam.php">Delete team</a></li>
      </ul>
    </nav>
    <h1>Team Details</h1>
    <p><a href="dashboard.php">Dashbored</a></p>
  </center>
  </header>



  <table>
    <tr>
      <td>Team Name</td>
      <td><?php echo $team['team_name']; ?></td>
    </tr>
    <tr>
      <td>Skill Level</td>
      <td><?php echo $team['skill_level']; ?></td>
    </tr>
    <tr>
      <td>Game Day</td>
      <td><?php echo $team['game_day']; ?></td>
    </tr>
  </table>

  <h3>Players</h3>

  <?php if (!empty($players)) { ?>
    <ul>
      <?php foreach ($players as $player) { ?>
        <li><?php echo $player['player_name']; ?></li>
      <?php } ?>
    </ul>
  <?php } else { ?>
    <p>No players found in the team.</p>
  <?php } ?>

  <ul>
    <li><a href="editTeam.php?team_id=<?php echo $teamId; ?>">Edit Team</a></li>
    <li>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?team_id=<?php echo $teamId; ?>" method="POST">
        <input type="hidden" name="team_id" value="<?php echo $teamId; ?>">
        <label for="player_name">Player Name:</label>
        <input type="text" name="player_name" required>
        <input type="submit" name="add_player" value="Add Player">
      </form>
      <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
      <?php } ?>
    </li>
    <li>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?team_id=<?php echo $teamId; ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this team?');">
        <input type="hidden" name="team_id" value="<?php echo $teamId; ?>">
        <input type="submit" name="delete_team" value="Delete Team">
      </form>
    </li>
  </ul>

  <footer>
    <p>&copy; 2023 Ghaid's Kickball League - final project. All rights reserved.</p>
  </footer>
</body>
</html>


