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

if (!$blog) {

    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo htmlspecialchars($blog["title"]); ?>
        - My Blog
    </title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <header>

        <h1>My Blog</h1>

        <nav>

            <a href="../index.php">
                Home
            </a>

            |

            <a href="../auth/register.php">
                Register
            </a>

            |

            <a href="../auth/login.php">
                Login
            </a>

        </nav>

    </header>


    <main>

        <article>

            <h1>
                <?php echo htmlspecialchars($blog["title"]); ?>
            </h1>


            <p>

                By
                <strong>
                    <?php echo htmlspecialchars($blog["username"]); ?>
                </strong>

            </p>


            <p>

                Published:
                <?php
                echo date(
                    "F d, Y",
                    strtotime($blog["created_at"])
                );
                ?>

            </p>


            <?php if ($blog["updated_at"] !== $blog["created_at"]): ?>

                <p>

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


            <div>

                <?php
                echo nl2br(
                    htmlspecialchars($blog["content"])
                );
                ?>

            </div>

        </article>


        <p>

            <a href="../index.php">
                ← Back to Home
            </a>

        </p>

    </main>

</body>

</html>