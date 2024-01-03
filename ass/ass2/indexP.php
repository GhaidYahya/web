<?php
require_once 'db.inc'; // Include the database connection file

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = $_POST['name'];
    $photo_Name = $_FILES['photo']['name'];
    $bio = $_POST['bio'];
    $cv_Name = $_FILES['cv']['name'];
    $area_of_experience = $_POST['area_of_experience'];
    $level_of_experience = $_POST['level_of_experience'];
    $area_of_interest = $_POST['area_of_interest'];

    move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photo_Name);
    move_uploaded_file($_FILES['cv']['tmp_name'], 'uploads/' . $cv_Name);

    $pdo = db_connect();

    if ($pdo !== false) {
        // Prepare the SQL statement to insert the profile into the database
        $stmt = $pdo->prepare("INSERT INTO userprofiles (name_p, photo_p, bio_p, cv_p, area_of_experience_p, level_of_experience_p, area_of_interest_p) 
                               VALUES (:name, :photo, :bio, :cv, :area_of_experience, :level_of_experience, :area_of_interest)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':photo', $photo_Name);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':cv', $cv_Name);
        $stmt->bindParam(':area_of_experience', $area_of_experience);
        $stmt->bindParam(':level_of_experience', $level_of_experience);
        $stmt->bindParam(':area_of_interest', $area_of_interest);

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
    <title>Create User Profile</title>
</head>
<body>
    <h1>Create User Profile</h1>

    <?php if ($success) { ?>
        <h2>Profile Created Successfully</h2>
        <h3>Name: <?php echo $name; ?></h3>
        <p>Brief Bio: <?php echo $bio; ?></p>
        <p>Area of Experience: <?php echo $area_of_experience; ?></p>
        <p>Level of Experience: <?php echo $level_of_experience; ?></p>
        <p>Area of Interest: <?php echo $area_of_interest; ?></p>
        <img src="uploads/<?php echo $photo_Name; ?>" alt="User Photo">
    <?php } else { ?>
        <?php if (!empty($error)) { ?>
            <p>Error: <?php echo $error; ?></p>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <br><br>

            <label for="photo">Photo (PNG, GIF, JPEG):</label>
            <input type="file" id="photo" name="photo" accept="image/png, image/gif, image/jpeg" required>
            <br><br>

            <label for="bio">Brief Bio:</label>
            <textarea id="bio" name="bio" rows="4" cols="50" required></textarea>
            <br><br>

            <label for="cv">CV (PDF):</label>
            <input type="file" id="cv" name="cv" accept="application/pdf" required>
            <br><br>

            <label for="area_of_experience">Area of Experience:</label>
            <input type="text" id="area_of_experience" name="area_of_experience" required>
            <br><br>

            <label for="level_of_experience">Level of Experience:</label>
            <select id="level_of_experience" name="level_of_experience" required>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="advanced">Advanced</option>
                <option value="expert">Expert</option>
            </select>
            <br><br>

            <label for="area_of_interest">Area of Interest:</label>
            <input type="text" id="area_of_interest" name="area_of_interest" required>
            <br><br>

            <button type="submit">Create Profile</button>
        </form>
    <?php } ?>
</body>
</html>
