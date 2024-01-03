<?php
require_once 'db.inc';

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $keywords = $_POST['keywords'];
    $body_text = $_POST['body_text'];

    // Get the file name
    $file_Name = $_POST['file_name'];

    $pdo = db_connect();

    if ($pdo !== false) {
        // Prepare the SQL statement to insert the article into the database
        $stmt = $pdo->prepare("INSERT INTO articles (title, description, keywords, body_text, file_Name) VALUES (:title, :description, :keywords, :body_text, :file_Name)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':keywords', $keywords);
        $stmt->bindParam(':body_text', $body_text);
        $stmt->bindParam(':file_Name', $file_Name);

        // Execute the query
        $stmt->execute();

        // Set success flag
        $success = true;
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
    <title>Create Article</title>
</head>
<body>
    <h1>Create Article</h1>

    <?php if ($success) { ?>
        <h2>Article Created Successfully</h2>
        <h3>Title: <?php echo $title; ?></h3>
        <p>Description: <?php echo $description; ?></p>
        <p>Keywords: <?php echo $keywords; ?></p>
        <p>Body Text: <?php echo $body_text; ?></p>
        <p>File Name: <?php echo $file_Name; ?></p>
    <?php } else { ?>
        <?php if (!empty($error)) { ?>
            <p>Error: <?php echo $error; ?></p>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <br>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            </br>
            <br>
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" required>
            </br>
            <br>
            <label for="keywords">Keywords:</label>
            <input type="text" id="keywords" name="keywords" required>
            </br>
            <br>
            <label for="body_text">Body Text:</label>
            <textarea id="body_text" name="body_text" rows="8" cols="50"></textarea>
            </br>
            <br>
            <label for="file_name">File Name:</label>
            <input type="text" id="file_name" name="file_name" required>
            </br>
            <button type="submit">Publish Article</button>
        </form>
    <?php } ?>
</body>
</html>
