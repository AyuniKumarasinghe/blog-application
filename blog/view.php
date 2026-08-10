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

$pageTitle = $blog["title"] . " - My Blog";

require_once "../includes/header.php";

if (!$blog) {

    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

</head>

<body>

    <header>

     

    </header>


    <main>

       <article class="single-blog">

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
            Published:
            <?php
            echo date(
                "F d, Y",
                strtotime($blog["created_at"])
            );
            ?>
        </span>

    </div>


    <?php if ($blog["updated_at"] !== $blog["created_at"]): ?>

        <p class="blog-meta">

            Updated:
            <?php
            echo date(
                "F d, Y",
                strtotime($blog["updated_at"])
            );
            ?>

        </p>

    <?php endif; ?>


    <hr>


    <div class="single-blog-content">

        <?php
        echo nl2br(
            htmlspecialchars($blog["content"])
        );
        ?>

    </div>


    <p style="margin-top: 30px;">

        <a
            href="../index.php"
            class="button"
        >
            ← Back to Home
        </a>

    </p>

</article>


    </main>

</body>

</html>
<?php require_once "../includes/footer.php"; ?>