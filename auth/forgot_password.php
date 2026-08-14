<?php

session_start();

require_once "../config/database.php";

$message = "";
$messageType = "";
$resetLink = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");

    if (empty($email)) {

        $message = "Please enter your email address.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } else {

        $sql = "SELECT id
                FROM user
                WHERE email = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            // Remove old tokens
            $deleteSql = "DELETE FROM password_resets
                          WHERE user_id = ?";

            $deleteStmt = $pdo->prepare($deleteSql);
            $deleteStmt->execute([$user["id"]]);


            // Generate secure token
            $token = bin2hex(random_bytes(32));

            // Store only token hash
            $tokenHash = hash("sha256", $token);


            // Token valid for 30 minutes
            $expiresAt = date(
                "Y-m-d H:i:s",
                time() + 1800
            );


            $insertSql = "INSERT INTO password_resets
                          (user_id, token_hash, expires_at)
                          VALUES (?, ?, ?)";

            $insertStmt = $pdo->prepare($insertSql);

            $insertStmt->execute([
                $user["id"],
                $tokenHash,
                $expiresAt
            ]);


            // Local XAMPP reset link
            $resetLink =
                "http://localhost/blog-application/auth/reset_password.php?token="
                . urlencode($token);
        }


        $message =
            "If an account with that email exists, a reset link has been generated.";

        $messageType = "success";
    }
}

$pageTitle = "Forgot Password - ChessUpdate";

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

            <h2>Forgot Password?</h2>

            <p>
                Enter your registered email address.
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


            <button
                type="submit"
                class="auth-button"
            >
                Generate Reset Link
            </button>

        </form>


        <?php if (!empty($resetLink)): ?>

            <div class="reset-testing-box">

                <strong>
                    Password Reset Link
                </strong>

                <p>
                    For local XAMPP testing, click the link below.
                </p>

                <a href="<?php echo htmlspecialchars($resetLink); ?>">
                    Reset Your Password
                </a>

            </div>

        <?php endif; ?>


        <div class="auth-switch">

            Remember your password?

            <a href="login.php">
                Login
            </a>

        </div>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>