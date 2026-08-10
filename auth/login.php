<?php

session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Email and password are required.";

    } else {

        $sql = "SELECT id, username, email, password, role
                FROM user
                WHERE email = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            header("Location: ../dashboard/index.php");
            exit;

        } else {

            $message = "Invalid email or password.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - My Blog</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <h1>Login</h1>

    <?php if (!empty($message)): ?>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST" action="">

        <div>

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                required
            >

        </div>


        <div>

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>


        <button type="submit">
            Login
        </button>

    </form>


    <p>
        Don't have an account?

        <a href="register.php">
            Register
        </a>
    </p>

</body>

</html>