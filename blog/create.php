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

    <div class="page-section">

    <h1>Create a Chess Blog ♟</h1>

    <p>
        Share your chess knowledge, game analysis,
        tournament news or strategies with the community.
    </p>

    <!-- YOUR EXISTING FORM HERE -->

</div>

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

            <div class="editor">

    <div class="editor-toolbar">

        <button
            type="button"
            onclick="formatText('bold')"
            title="Bold"
        >
            <strong>B</strong>
        </button>

        <button
            type="button"
            onclick="formatText('italic')"
            title="Italic"
        >
            <em>I</em>
        </button>

        <button
            type="button"
            onclick="formatText('underline')"
            title="Underline"
        >
            <u>U</u>
        </button>

        <button
            type="button"
            onclick="formatText('insertUnorderedList')"
            title="Bullet list"
        >
            • List
        </button>

        <button
            type="button"
            onclick="formatText('insertOrderedList')"
            title="Numbered list"
        >
            1. List
        </button>

    </div>


    <div
        id="blogEditor"
        class="editor-area"
        contenteditable="true"
    ></div>


    <input
        type="hidden"
        name="content"
        id="blogContent"
    >

</div>

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