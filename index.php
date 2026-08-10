<?php

require_once "config/database.php";

$sql = "SELECT blogPost.id,
               blogPost.title,
               blogPost.content,
               blogPost.created_at,
               user.username
        FROM blogPost
        INNER JOIN user
        ON blogPost.user_id = user.id
        ORDER BY blogPost.created_at DESC";

$stmt = $pdo->query($sql);

$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Home - My Blog";

require_once "includes/header.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>

   
</head>

<body>

    <header>

       

    </header>


    <main>

        <h2>Latest Blog Posts</h2>


        <?php if (empty($blogs)): ?>

            <p>
                No blog posts available yet.
            </p>

        <?php else: ?>

            <?php foreach ($blogs as $blog): ?>

               <article class="blog-card">

                    <h3>
                        <?php echo htmlspecialchars($blog["title"]); ?>
                    </h3>


                    <p>
                        By
                        <?php echo htmlspecialchars($blog["username"]); ?>
                    </p>


                    <p>
                        <?php
                        echo date(
                            "F d, Y",
                            strtotime($blog["created_at"])
                        );
                        ?>
                    </p>


                    <p>

                        <?php
                        $shortContent = strlen($blog["content"]) > 150
                            ? substr($blog["content"], 0, 150) . "..."
                            : $blog["content"];

                        echo htmlspecialchars($shortContent);
                        ?>

                    </p>


    <a
    href="blog/view.php?id=<?php echo $blog["id"]; ?>"
    class="button"
>
    Read More
</a>
                </article>

                <hr>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>

<?php require_once "includes/footer.php"; ?>