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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Blog</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <header>

        <h1>My Blog</h1>

        <nav>

            <a href="index.php">
                Home
            </a>

            |

            <a href="auth/register.php">
                Register
            </a>

            |

            <a href="auth/login.php">
                Login
            </a>

        </nav>

    </header>


    <main>

        <h2>Latest Blog Posts</h2>


        <?php if (empty($blogs)): ?>

            <p>
                No blog posts available yet.
            </p>

        <?php else: ?>

            <?php foreach ($blogs as $blog): ?>

                <article>

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


                    <a href="blog/view.php?id=<?php echo $blog["id"]; ?>">
                        Read More
                    </a>

                </article>

                <hr>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>

</body>

</html>