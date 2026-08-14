<?php

session_start();

require_once "../config/database.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";


    if (
        empty($username) ||
        empty($email) ||
        empty($password) ||
        empty($confirmPassword)
    ) {

        $message = "All fields are required.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif (strlen($username) < 3) {

        $message = "Username must contain at least 3 characters.";
        $messageType = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $messageType = "error";

    } elseif ($password !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "error";

    } else {

        $sql = "SELECT id
                FROM user
                WHERE email = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $message =
                "An account with this email already exists.";

            $messageType = "error";

        } else {

            $hashedPassword =
                password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

            $sql = "INSERT INTO user
                    (username, email, password, role)
                    VALUES (?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $username,
                $email,
                $hashedPassword,
                "user"
            ]);

            $message =
                "Registration successful! You can now log in.";

            $messageType = "success";
        }
    }
}

$pageTitle = "Register - ChessUpdate";

require_once "../includes/header.php";

?>

<section class="auth-page">

    <div class="auth-card">

        <div class="auth-brand">

            <div class="auth-logo">
                ♟
            </div>

            <h1>
                ChessUpdate
            </h1>

            <p>
                Stay Ahead. Stay Updated. Play Better.
            </p>

        </div>


        <div class="auth-heading">

            <h2>
                Create Your Account
            </h2>

            <p>
                Join the ChessUpdate community.
            </p>

        </div>


        <?php if (!empty($message)): ?>

            <div
                class="message
                <?php
                echo $messageType === "success"
                    ? "success-message"
                    : "error-message";
                ?>"
            >

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST" action="">

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    minlength="3"
                    maxlength="50"
                    autocomplete="username"
                    required
                >

            </div>


            <div class="form-group">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="6"
                    autocomplete="new-password"
                    required
                >

            </div>


            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="6"
                    autocomplete="new-password"
                    required
                >

            </div>


            <button
                type="submit"
                class="auth-button"
            >
                Create Account
            </button>

        </form>


        <div class="auth-switch">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </div>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>