<?php

require_once "config/database.php";

$sql = "SELECT blogPost.id,
               blogPost.title,
               blogPost.content,
               blogPost.created_at,
               blogPost.image,
               user.username
        FROM blogPost
        INNER JOIN user
        ON blogPost.user_id = user.id
        ORDER BY blogPost.created_at DESC";

$stmt = $pdo->query($sql);

$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * Helper function to generate clean text preview/excerpt from HTML content
 */
function getCleanExcerpt($content, $limit = 150) {
    // 1. Decode HTML entities (e.g. &lt;p&gt; -> <p>)
    $decoded = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
    
    // 2. Strip all HTML tags to get pure plain text
    $plainText = strip_tags($decoded);
    
    // 3. Remove extra spaces, tabs, or newlines
    $plainText = trim(preg_replace('/\s+/', ' ', $plainText));
    
    // 4. Truncate text cleanly using multibyte string functions
    if (mb_strlen($plainText) > $limit) {
        $plainText = mb_substr($plainText, 0, $limit) . '...';
    }
    
    return htmlspecialchars($plainText, ENT_QUOTES, 'UTF-8');
}

$pageTitle = "ChessUpdate - Home";

require_once "includes/header.php";
?>


<section class="hero">

    <?php if (isset($_SESSION["user_id"])): ?>

        <h1>
            Welcome back,
            <?php echo htmlspecialchars($_SESSION["username"]); ?> ♟
        </h1>

        <p>
            Welcome to ChessUpdate — explore chess techniques,
            tournament news, famous games and the latest stories
            from the world of chess.
        </p>

    <?php else: ?>

        <h1>
            Welcome to ChessUpdate ♟
        </h1>

        <p>
            Discover chess techniques, legendary games,
            championship news and stories from the world of chess.
        </p>

        <a
            href="auth/register.php"
            class="button"
        >
            Join ChessUpdate
        </a>

    <?php endif; ?>

</section>


<section>

    <div class="section-heading">

        <div>
            <h2>Featured Chess Stories</h2>

            <p>
                Explore ideas that every chess enthusiast can enjoy.
            </p>
        </div>

    </div>


    <div class="featured-grid">

        <article class="featured-card">

            <div class="featured-icon">
                ♟
            </div>

            <h3>Chess Techniques</h3>

            <p>
                Learn useful opening ideas, tactical patterns,
                endgame techniques and strategic concepts.
            </p>

        </article>


        <article class="featured-card">

            <div class="featured-icon">
                ♜
            </div>

            <h3>Latest Games</h3>

            <p>
                Follow interesting games and discover
                important moments from recent competitions.
            </p>

        </article>


        <article class="featured-card">

            <div class="featured-icon">
                ♛
            </div>

            <h3>Championship News</h3>

            <p>
                Explore tournament stories, championship
                highlights and memorable performances.
            </p>

        </article>

    </div>

</section>


<main>

    <h2>Latest Blog Posts</h2>


    <?php if (empty($blogs)): ?>

        <p>
            No blog posts available yet.
        </p>

    <?php else: ?>

        <div class="blog-grid">

            <?php foreach ($blogs as $blog): ?>

                <article class="blog-card">


                    <?php if (!empty($blog["image"])): ?>

                        <div class="blog-card-image">

                            <img
                                src="assets/uploads/blogs/<?php echo htmlspecialchars($blog["image"]); ?>"
                                alt="<?php echo htmlspecialchars($blog["title"]); ?>"
                            >

                        </div>

                    <?php endif; ?>


                    <div class="blog-card-content">

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


                        <!-- Cleaned & Safe Excerpt -->
                        <p>
                            <?php echo getCleanExcerpt($blog["content"]); ?>
                        </p>


                        <a
                            href="blog/view.php?id=<?php echo $blog["id"]; ?>"
                            class="button"
                        >
                            Read More
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</main>


<?php require_once "includes/footer.php"; ?>