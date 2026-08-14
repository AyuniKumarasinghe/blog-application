<?php

session_start();

require_once "../config/database.php";

$message = "";
$messageType = "";

$token = trim(
    $_GET["token"] ??
    $_POST["token"] ??
    ""
);

$validToken = false;
$resetRecord = null;


/*
|--------------------------------------------------------------------------
| STEP 1: Check the reset token
|--------------------------------------------------------------------------
*/

if (!empty($token)) {

    // Convert the token into the same hash
    // that was stored in the database.
    $tokenHash = hash("sha256", $token);


    $sql = "SELECT id, user_id, expires_at
            FROM password_resets
            WHERE token_hash = ?
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $tokenHash
    ]);

    $resetRecord = $stmt->fetch(PDO::FETCH_ASSOC);


    /*
     * Check whether the token exists.
     */
    if ($resetRecord) {

        /*
         * Check expiry using PHP time.
         * This avoids MySQL/PHP timezone problems.
         */

        $expiryTime = strtotime(
            $resetRecord["expires_at"]
        );

        if ($expiryTime > time()) {

            $validToken = true;

        } else {

            $message =
                "This reset link has expired.";

            $messageType = "error";
        }

    } else {

        $message =
            "This reset link is invalid.";

        $messageType = "error";
    }
}


/*
|--------------------------------------------------------------------------
| STEP 2: Process new password
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST["password"] ?? "";

    $confirmPassword =
        $_POST["confirm_password"] ?? "";


    /*
     * Check token again.
     */
    if (!$validToken) {

        $message =
            "This reset link is invalid or has expired.";

        $messageType = "error";


    /*
     * Check password fields.
     */
    } elseif (
        empty($password) ||
        empty($confirmPassword)
    ) {

        $message =
            "Please fill in both password fields.";

        $messageType = "error";


    /*
     * Password length.
     */
    } elseif (strlen($password) < 6) {

        $message =
            "Password must be at least 6 characters.";

        $messageType = "error";


    /*
     * Confirm password.
     */
    } elseif ($password !== $confirmPassword) {

        $message =
            "Passwords do not match.";

        $messageType = "error";


    } else {

        /*
         * Securely hash the new password.
         */
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        /*
         * Update the user's password.
         */
        $updateSql = "UPDATE user
                      SET password = ?
                      WHERE id = ?";

        $updateStmt = $pdo->prepare(
            $updateSql
        );

        $updateStmt->execute([
            $hashedPassword,
            $resetRecord["user_id"]
        ]);


        /*
         * Delete the reset token.
         *
         * This means the same reset link
         * cannot be used twice.
         */
        $deleteSql = "DELETE FROM password_resets
                      WHERE id = ?";

        $deleteStmt = $pdo->prepare(
            $deleteSql
        );

        $deleteStmt->execute([
            $resetRecord["id"]
        ]);


        /*
         * Success message.
         */
        $message =
            "Your password has been changed successfully.";

        $messageType = "success";

        $validToken = false;
    }
}


$pageTitle = "Reset Password - ChessUpdate";

require_once "../includes/header.php";

?>


<section class="auth-page">

    <div class="auth-card">


        <!-- ChessUpdate Branding -->

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


        <!-- Heading -->

        <div class="auth-heading">

            <h2>
                Reset Password
            </h2>

            <p>
                Create a new password for your account.
            </p>

        </div>


        <!-- Message -->

        <?php if (!empty($message)): ?>

            <div
                class="message
                <?php
                echo $messageType === "success"
                    ? "success-message"
                    : "error-message";
                ?>"
            >

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php endif; ?>


        <!-- PASSWORD FORM -->

        <?php if ($validToken): ?>

            <form
                method="POST"
                action=""
            >

                <!-- Keep token hidden -->

                <input
                    type="hidden"
                    name="token"
                    value="<?php
                    echo htmlspecialchars($token);
                    ?>"
                >


                <!-- New Password -->

                <div class="form-group">

                    <label for="password">
                        New Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        minlength="6"
                        autocomplete="new-password"
                        placeholder="Enter new password"
                        required
                    >

                </div>


                <!-- Confirm Password -->

                <div class="form-group">

                    <label for="confirm_password">
                        Confirm New Password
                    </label>

                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        minlength="6"
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                        required
                    >

                </div>


                <!-- Submit -->

                <button
                    type="submit"
                    class="auth-button"
                >
                    Reset Password
                </button>

            </form>


        <?php elseif ($messageType === "success"): ?>


            <div class="auth-switch">

                <a href="login.php">
                    Return to Login
                </a>

            </div>


        <?php else: ?>


            <div class="auth-switch">

                The reset link is invalid or expired.

                <br><br>

                <a href="forgot_password.php">
                    Request a new reset link
                </a>

            </div>


        <?php endif; ?>


    </div>

</section>


<?php require_once "../includes/footer.php"; ?>