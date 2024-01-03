<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
<center>
  <img src="fbzuLOGO.png" alt="Football Logo" height="180" width="230">
    <h1>Welcome <?php echo $username; ?></h1>
    <h3><a href="../../project/index.html">Log out</a></h3>
    <h2>Dashboard</h2>
</center>
   
  </header>

  <main>
    <table>
      <thead>
        <tr>
          <th>Team Name</th>
          <th>Skill Level</th>
          <th>Number of Players</th>
          <th>Game Day</th>
        </tr>
      </thead>
      <tbody>
        <?php
        require_once 'db.inc';
        $pdo = db_connect();
        if ($pdo) {
          $stmt = $pdo->query("SELECT * FROM teams");
          $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

          foreach ($teams as $team) {
            $teamId = $team['team_id'];
            $teamName = $team['team_name'];
            $skillLevel = $team['skill_level'];
            $gameDay = $team['game_day'];

            // Retrieve the number of players in the team
            $playerCountStmt = $pdo->prepare("SELECT COUNT(*) AS player_count FROM players WHERE team_id = :team_id");
            $playerCountStmt->bindValue(':team_id', $teamId, PDO::PARAM_INT);
            $playerCountStmt->execute();
            $playerCountRow = $playerCountStmt->fetch(PDO::FETCH_ASSOC);
            $playerCount = $playerCountRow['player_count'];
            ?>
            <tr>
              <td><a href="teamDetails.php?team_id=<?php echo $teamId; ?>"><?php echo $teamName; ?></a></td>
              <td><?php echo $skillLevel; ?></td>
              <td><?php echo $playerCount; ?></td>
              <td><?php echo $gameDay; ?></td>
            </tr>
            <?php
          }
        } else {
          echo "Failed to connect to the database.";
        }
        ?>
      </tbody>
    </table>

    <button onclick="location.href='createTeam.php'" class="create-team-button">
      <img src="organization.png" alt="Create Team Icon" class="create-team-icon" height="30" width="20">
      Create New Team
    </button>
  </main>

  <footer>
  <p>&copy; 2023 Ghaid's Kickball League - final project. All rights reserved.</p>
  </footer>
</body>
</html>

