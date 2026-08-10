<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: ../auth/login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - My Blog</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <h1>Dashboard</h1>

    <h2>
        Welcome,
        <?php echo htmlspecialchars($_SESSION["username"]); ?>!
    </h2>

    <p>
        You are successfully logged in.
    </p>

    <p>
        Your email:
        <?php echo htmlspecialchars($_SESSION["email"]); ?>
    </p>

    <p>
        <a href="../index.php">
            Home
        </a>
    </p>

    <p>
        <a href="../auth/logout.php">
            Logout
        </a>
    </p>

</body>

</html>