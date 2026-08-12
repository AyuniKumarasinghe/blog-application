<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo isset($pageTitle) ? $pageTitle : "ChessUpdates"; ?>
    </title>

   <link rel="stylesheet" href="/blog-application/assets/css/style.css?v=<?php echo filemtime($_SERVER['DOCUMENT_ROOT'] . '/blog-application/assets/css/style.css'); ?>">

</head>

<body>

<header class="site-header">

    <div class="container navbar">

 <a href="/blog-application/index.php" class="logo">
    ♟ ChessUpdates
</a>

        <nav class="nav-links">

    <a href="/blog-application/index.php">
        Home
    </a>

    <?php if (isset($_SESSION["user_id"])): ?>

        <a
            href="/blog-application/dashboard/index.php"
            class="nav-button"
        >
            Dashboard
        </a>

        <a
            href="/blog-application/blog/create.php"
            class="nav-button"
        >
            Create Blog
        </a>

        <a
            href="/blog-application/auth/logout.php"
            class="nav-button"
        >
            Logout
        </a>

    <?php else: ?>

        <a href="/blog-application/auth/login.php">
            Login
        </a>

        <a href="/blog-application/auth/register.php">
            Register
        </a>

    <?php endif; ?>

</nav>

    </div>

</header>

<main class="container">