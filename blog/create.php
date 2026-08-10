<?php

session_start();

require_once "../config/database.php";


$pageTitle = "Create Blog - My Blog";

require_once "../includes/header.php";

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

</head>

<body>
<section class="page-section">

    <h1>Create New Blog</h1>

    <?php if (!empty($message)): ?>

        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST" action="">

        <div>

            <label for="title">
                Blog Title
            </label>

            <input
                type="text"
                id="title"
                name="title"
                placeholder="Enter your blog title"
                required
            >

        </div>


        <div>

            <label for="content">
                Blog Content
            </label>

            <textarea
                id="content"
                name="content"
                rows="12"
                placeholder="Write your blog here..."
                required
            ></textarea>

        </div>


        <button type="submit">
            Publish Blog
        </button>

    </form>

</section>

    <p>
        <a href="../dashboard/index.php">
            Back to Dashboard
        </a>
    </p>

</body>

</html>
<?php require_once "../includes/footer.php"; ?>