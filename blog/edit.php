<?php

session_start();

require_once "../config/database.php";

// User must be logged in
if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;
}

// Check blog ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: ../index.php");
    exit;
}

$blogId = (int) $_GET["id"];


// Get the blog
$sql = "SELECT id, user_id, title, content
        FROM blogPost
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$blogId]);

$blog = $stmt->fetch(PDO::FETCH_ASSOC);


// Blog does not exist
if (!$blog) {

    header("Location: ../index.php");
    exit;
}


// Authorization check
if ($blog["user_id"] != $_SESSION["user_id"]) {

    die("Access denied. You can only edit your own blogs.");
}


$message = "";


// Update blog
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);


    if (empty($title) || empty($content)) {

        $message = "Title and content are required.";

    } else {

        $sql = "UPDATE blogPost
                SET title = ?, content = ?
                WHERE id = ? AND user_id = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $title,
            $content,
            $blogId,
            $_SESSION["user_id"]
        ]);


        header("Location: view.php?id=" . $blogId);
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Blog - My Blog</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <h1>Edit Blog</h1>


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
                value="<?php echo htmlspecialchars($blog["title"]); ?>"
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
                rows="12"
                required
            ><?php echo htmlspecialchars($blog["content"]); ?></textarea>

        </div>

        <br>


        <button type="submit">
            Update Blog
        </button>

    </form>


    <p>

        <a href="view.php?id=<?php echo $blogId; ?>">
            Cancel
        </a>

    </p>

</body>

</html>