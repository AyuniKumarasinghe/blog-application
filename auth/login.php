<?php

session_start();

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

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

            session_regenerate_id(true);

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

$pageTitle = "Login - ChessUpdate";

require_once "../includes/header.php";

?>

<section class="auth-page">

    <div class="auth-card">

        <div class="auth-brand">

            <div class="auth-logo">
                ♟
            </div>

            <h1>ChessUpdate</h1>

            <p>
                Stay Ahead. Stay Updated. Play Better.
            </p>

        </div>


        <div class="auth-heading">

            <h2>Welcome Back</h2>

            <p>
                Login to continue to your chess world.
            </p>

        </div>


        <?php if (!empty($message)): ?>

            <div class="message error-message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST" action="">

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
                    autocomplete="current-password"
                    required
                >

            </div>


            <!-- FORGOT PASSWORD -->

            <div class="forgot-password">

                <a href="forgot_password.php">
                    Forgot Password?
                </a>

            </div>


            <button
                type="submit"
                class="auth-button"
            >
                Login
            </button>

        </form>


        <div class="auth-switch">

            Don't have an account?

            <a href="register.php">
                Register
            </a>

        </div>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>