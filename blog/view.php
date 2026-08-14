<?php

require_once "../config/database.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: ../index.php");
    exit;
}

$blogId = (int) $_GET["id"];

$sql = "SELECT blogPost.id,
               blogPost.title,
               blogPost.content,
               blogPost.image,
               blogPost.created_at,
               blogPost.updated_at,
               user.username
        FROM blogPost
        INNER JOIN user
        ON blogPost.user_id = user.id
        WHERE blogPost.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$blogId]);

$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
    header("Location: ../index.php");
    exit;
}

$pageTitle = $blog["title"] . " - ChessUpdate";

require_once "../includes/header.php";

/*
 * Allow only the formatting tags that our editor creates.
 * This prevents unwanted HTML/JavaScript from being displayed.
 */
$safeContent = strip_tags(
    $blog["content"],
    "<p><br><strong><b><em><i><u><ul><ol><li>"
);

?>

<section class="single-blog">

    <div class="single-blog-header">

        <span class="blog-label">
            ♟ ChessUpdate
        </span>

        <h1>
            <?php echo htmlspecialchars($blog["title"]); ?>
        </h1>

        <div class="blog-meta">

            <span>
                By
                <strong>
                    <?php echo htmlspecialchars($blog["username"]); ?>
                </strong>
            </span>

            <span>
                📅
                <?php
                echo date(
                    "F d, Y",
                    strtotime($blog["created_at"])
                );
                ?>
            </span>

        </div>

    </div>


    <?php if (!empty($blog["image"])): ?>

        <div class="single-blog-image">

            <img
                src="../assets/uploads/blogs/<?php echo htmlspecialchars($blog["image"]); ?>"
                alt="<?php echo htmlspecialchars($blog["title"]); ?>"
            >

        </div>

    <?php endif; ?>


    <div class="single-blog-content">

        <?php echo $safeContent; ?>

    </div>


    <div class="single-blog-actions">

        <a
            href="../index.php"
            class="button secondary-button"
        >
            ← Back to Home
        </a>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>