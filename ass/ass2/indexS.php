<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Articles</title>
</head>
<body>
    <h1>Search Articles</h1>

    <form action="indexS.php" method="GET">
        <label for="searchInput">Search Keywords:</label>
        <input type="text" id="searchInput" name="keywords" required>
        <button type="submit">Search</button>
    </form>

    <?php
    $host = 'localhost';
    $dbname = 'c139_ghaid_2023';
    $username = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Failed to connect to the database: " . $e->getMessage());
    }

    if (isset($_GET['keywords'])) {
        $keywords = $_GET['keywords'];

        $stmt = $pdo->prepare("SELECT * FROM articles WHERE title LIKE :keywords OR description LIKE :keywords");
        $stmt->bindValue(':keywords', '%' . $keywords . '%');
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h2>Search Results</h2>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $row) { ?>
                <tr>
                    <td><a href="article.php?id=<?php echo $row['article_id']; ?>"><?php echo $row['title']; ?></a></td>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</body>
</html>
