<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$sql = "SELECT id,
               title,
               content,
               image,
               created_at,
               updated_at
        FROM blogPost
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $_SESSION["user_id"]
]);

$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Dashboard - ChessUpdate";

require_once "../includes/header.php";

?>

<section class="dashboard-page">

    <div class="dashboard-header">

        <div>

            <span class="dashboard-label">
                ♟ ChessUpdate
            </span>

            <h1>
                Welcome,
                <?php echo htmlspecialchars($_SESSION["username"]); ?>!
            </h1>

            <p>
                Manage your chess stories, game analysis
                and latest updates.
            </p>

        </div>


        <div class="dashboard-actions">

            <a
                href="../blog/create.php"
                class="button"
            >
                ✍ Create New Blog
            </a>

            <a
                href="../index.php"
                class="button secondary-button"
            >
                ← Home
            </a>

        </div>

    </div>


    <div class="section-heading">

        <div>

            <h2>
                My Blog Posts
            </h2>

            <p>
                Your published chess content.
            </p>

        </div>

    </div>


    <?php if (empty($blogs)): ?>

        <div class="empty-state">

            <div class="empty-icon">
                ♟
            </div>

            <h3>
                No blogs yet
            </h3>

            <p>
                Start sharing your chess knowledge.
            </p>

            <a
                href="../blog/create.php"
                class="button"
            >
                Create Your First Blog
            </a>

        </div>

    <?php else: ?>

        <div class="dashboard-grid">

            <?php foreach ($blogs as $blog): ?>

                <article class="dashboard-card">

                    <?php if (!empty($blog["image"])): ?>

                        <div class="dashboard-image">

                            <img
                                src="../assets/uploads/blogs/<?php
                                echo htmlspecialchars($blog["image"]);
                                ?>"
                                alt=""
                            >

                        </div>

                    <?php endif; ?>


                    <div class="dashboard-card-content">

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $blog["title"]
                            );
                            ?>
                        </h3>


                        <div class="blog-meta">

                            <span>
                                📅
                                <?php
                                echo date(
                                    "F d, Y",
                                    strtotime(
                                        $blog["created_at"]
                                    )
                                );
                                ?>
                            </span>

                        </div>


                        <p>

                            <?php

                            $plainText = strip_tags(
                                $blog["content"]
                            );

                            $shortContent =
                                strlen($plainText) > 120
                                ? substr(
                                    $plainText,
                                    0,
                                    120
                                ) . "..."
                                : $plainText;

                            echo htmlspecialchars(
                                $shortContent
                            );

                            ?>

                        </p>


                        <div class="blog-actions">

                            <a
                                href="../blog/view.php?id=<?php echo $blog["id"]; ?>"
                                class="action-button view-action"
                            >
                                View
                            </a>


                            <a
                                href="../blog/edit.php?id=<?php echo $blog["id"]; ?>"
                                class="action-button edit-action"
                            >
                                Edit
                            </a>


                            <a
                                href="../blog/delete.php?id=<?php echo $blog["id"]; ?>"
                                class="action-button delete-action delete-link"
                            >
                                Delete
                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>

<?php require_once "../includes/footer.php"; ?>