<?php

session_start();

require_once "../config/database.php";

// Make sure the user is logged in
if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    // Check empty fields
    if (empty($title) || empty($content)) {

        $message = "Title and content are required.";

    } else {

        $sql = "INSERT INTO blogPost (user_id, title, content)
                VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $_SESSION["user_id"],
            $title,
            $content
        ]);

        header("Location: ../dashboard/index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Blog - My Blog</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <h1>Create New Blog</h1>

    <?php if (!empty($message)): ?>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST" action="">

        <div>

            <label for="title">
                Blog Title
            </label>

            <br>

            <input
                type="text"
                id="title"
                name="title"
                placeholder="Enter your blog title"
                required
            >

        </div>

        <br>


        <div>

            <label for="content">
                Blog Content
            </label>

            <br>

            <textarea
                id="content"
                name="content"
                rows="10"
                placeholder="Write your blog here..."
                required
            ></textarea>

        </div>

        <br>


        <button type="submit">
            Publish Blog
        </button>

    </form>


    <p>
        <a href="../dashboard/index.php">
            Back to Dashboard
        </a>
    </p>

</body>

</html>