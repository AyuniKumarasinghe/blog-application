<?php

require_once "../config/database.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Check empty fields
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {

        $message = "All fields are required.";
        $messageType = "error";

    // Check email
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    // Check password length
    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $messageType = "error";

    // Check password confirmation
    } elseif ($password !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "error";

    } else {

        // Check whether email already exists
        $sql = "SELECT id FROM user WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $message = "An account with this email already exists.";
            $messageType = "error";

        } else {

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $sql = "INSERT INTO user (username, email, password, role)
                    VALUES (?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $username,
                $email,
                $hashedPassword,
                "user"
            ]);

            $message = "Registration successful! You can now log in.";
            $messageType = "success";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - My Blog</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

    <h1>Create an Account</h1>

    <?php if (!empty($message)): ?>

        <p>
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST" action="">

        <div>

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                required
            >

        </div>


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


        <div>

            <label for="confirm_password">
                Confirm Password
            </label>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                required
            >

        </div>


        <button type="submit">
            Register
        </button>

    </form>


    <p>
        Already have an account?

        <a href="login.php">
            Login
        </a>
    </p>

</body>

</html>