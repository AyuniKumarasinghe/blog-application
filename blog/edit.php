<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: ../index.php");
    exit;
}

$blogId = (int) $_GET["id"];

$sql = "SELECT id, user_id, title, content, image
        FROM blogPost
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$blogId]);

$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    header("Location: ../index.php");
    exit;
}

if ($blog["user_id"] != $_SESSION["user_id"]) {
    die("Access denied. You can only edit your own blogs.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");

    if (empty($title) || empty($content)) {

        $message = "Title and content are required.";

    } else {

        /*
         * Only allow formatting created by our editor.
         */
        $content = strip_tags(
            $content,
            "<p><br><strong><b><em><i><u><ul><ol><li>"
        );

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

$pageTitle = "Edit Blog - ChessUpdate";

require_once "../includes/header.php";

?>

<section class="page-section">

    <div class="form-card">

        <div class="form-card-header">

            <span class="form-icon">♟</span>

            <h1>Edit Your Chess Blog</h1>

            <p>
                Update your chess story, analysis or news.
            </p>

        </div>


        <?php if (!empty($message)): ?>

            <div class="message error-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <form method="POST" action="">

            <div class="form-group">

                <label for="title">
                    Blog Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?php echo htmlspecialchars($blog["title"]); ?>"
                    maxlength="200"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Blog Content
                </label>

                <div class="editor">

                    <div class="editor-toolbar">

                        <button
                            type="button"
                            onclick="formatText('bold')"
                        >
                            <strong>B</strong>
                        </button>

                        <button
                            type="button"
                            onclick="formatText('italic')"
                        >
                            <em>I</em>
                        </button>

                        <button
                            type="button"
                            onclick="formatText('underline')"
                        >
                            <u>U</u>
                        </button>

                        <button
                            type="button"
                            onclick="formatText('insertUnorderedList')"
                        >
                            • List
                        </button>

                        <button
                            type="button"
                            onclick="formatText('insertOrderedList')"
                        >
                            1. List
                        </button>

                    </div>


                    <div
                        id="blogEditor"
                        class="editor-area"
                        contenteditable="true"
                    ><?php echo $blog["content"]; ?></div>


                    <input
                        type="hidden"
                        name="content"
                        id="blogContent"
                    >

                </div>

            </div>


            <div class="form-actions">

                <button type="submit">
                    Update Blog
                </button>

                <a
                    href="view.php?id=<?php echo $blogId; ?>"
                    class="button secondary-button"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>