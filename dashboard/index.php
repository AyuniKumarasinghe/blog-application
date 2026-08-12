<?php

session_start();

require_once "../config/database.php";

// User must be logged in
if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;
}


// Get blogs created by the logged-in user
$sql = "SELECT id, title, content, created_at, updated_at
        FROM blogPost
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $_SESSION["user_id"]
]);

$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Dashboard - My Blog";

require_once "../includes/header.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    
</head>

<body>

    <header>

     

    </header>


    <main>

        <h2>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["username"]); ?>!
        </h2>

<div class="dashboard-header">

    <h1>
        Your Chess Dashboard ♟
    </h1>

    <p>
        Manage your chess stories, game analysis and latest updates.
    </p>

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
            ← Back to Home
        </a>

    </div>

</div>
        

       

        <h2>My Blog Posts</h2>


        <?php if (empty($blogs)): ?>

            <p>
                You have not created any blog posts yet.
            </p>

            <p>

                <a href="../blog/create.php">
                    Create your first blog
                </a>

            </p>

        <?php else: ?>
<div class="dashboard-grid">

            <?php foreach ($blogs as $blog): ?>

                <div class="dashboard-card">
               <article class="blog-card">

                    <h3>
                        <?php echo htmlspecialchars($blog["title"]); ?>
                    </h3>


                    <p>

                        Created:
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


                    <p>

                        <?php

                        $shortContent = strlen($blog["content"]) > 120
                            ? substr($blog["content"], 0, 120) . "..."
                            : $blog["content"];

                        echo htmlspecialchars($shortContent);

                        ?>

                    </p>


                    <p>

                        <a href="../blog/view.php?id=<?php echo $blog["id"]; ?>">
                            View
                        </a>

                        |

                        <a href="../blog/edit.php?id=<?php echo $blog["id"]; ?>">
                            Edit
                        </a>

                        |

                      <a
    href="../blog/delete.php?id=<?php echo $blog["id"]; ?>"
    class="delete-link"
>
    Delete
</a>

                    </p>

                </article>

                 </div>

            <?php endforeach; ?>
 </div>

        <?php endif; ?>



    </main>

</body>

</html>
<?php require_once "../includes/footer.php"; ?>