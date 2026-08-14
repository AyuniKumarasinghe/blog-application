<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? "");
    $content = trim($_POST["content"] ?? "");
    $imageName = null;


    if (empty($title) || empty($content)) {

        $message =
            "Title and content are required.";

        $messageType = "error";

    } else {

        /*
         * Allow only the formatting created by our editor.
         */
        $content = strip_tags(
            $content,
            "<p><br><strong><b><em><i><u><ul><ol><li>"
        );


        /*
         * OPTIONAL IMAGE UPLOAD
         */
        if (
            isset($_FILES["image"]) &&
            $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
        ) {

            if (
                $_FILES["image"]["error"]
                !== UPLOAD_ERR_OK
            ) {

                $message =
                    "There was a problem uploading the image.";

                $messageType = "error";

            } else {

                $allowedTypes = [
                    "image/jpeg",
                    "image/png",
                    "image/webp"
                ];

                $fileType =
                    mime_content_type(
                        $_FILES["image"]["tmp_name"]
                    );

                $fileSize =
                    $_FILES["image"]["size"];


                if (
                    !in_array(
                        $fileType,
                        $allowedTypes,
                        true
                    )
                ) {

                    $message =
                        "Only JPG, PNG and WebP images are allowed.";

                    $messageType = "error";

                } elseif ($fileSize > 5 * 1024 * 1024) {

                    $message =
                        "Image must be smaller than 5MB.";

                    $messageType = "error";

                } else {

                    $uploadDirectory =
                        "../assets/uploads/blogs/";

                    if (
                        !is_dir(
                            $uploadDirectory
                        )
                    ) {

                        mkdir(
                            $uploadDirectory,
                            0755,
                            true
                        );
                    }


                    $extension = strtolower(
                        pathinfo(
                            $_FILES["image"]["name"],
                            PATHINFO_EXTENSION
                        )
                    );


                    $imageName =
                        bin2hex(
                            random_bytes(16)
                        )
                        . "."
                        . $extension;


                    $destination =
                        $uploadDirectory
                        . $imageName;


                    if (
                        !move_uploaded_file(
                            $_FILES["image"]["tmp_name"],
                            $destination
                        )
                    ) {

                        $message =
                            "Unable to save the image.";

                        $messageType = "error";

                        $imageName = null;
                    }
                }
            }
        }


        if (empty($message)) {

            $sql = "INSERT INTO blogPost
                    (user_id, title, content, image)
                    VALUES (?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $_SESSION["user_id"],
                $title,
                $content,
                $imageName
            ]);

            header("Location: ../dashboard/index.php");
            exit;
        }
    }
}

$pageTitle = "Create Blog - ChessUpdate";

require_once "../includes/header.php";

?>

<section class="page-section">

    <div class="form-card">

        <div class="form-card-header">

            <span class="form-icon">
                ♟
            </span>

            <h1>
                Create a Chess Blog
            </h1>

            <p>
                Share your chess knowledge, game analysis,
                tournament news or strategies.
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


        <form
            method="POST"
            action=""
            enctype="multipart/form-data"
        >

            <div class="form-group">

                <label for="title">
                    Blog Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    maxlength="200"
                    placeholder="Enter your blog title"
                    required
                >

            </div>


            <div class="form-group">

                <label>
                    Blog Content
                </label>


                <div class="editor">

                    <div class="editor-toolbar">

                        <button
                            type="button"
                            onclick="formatText('bold')"
                        >
                            <strong>B</strong>
                        </button>

                        <button
                            type="button"
                            onclick="formatText('italic')"
                        >
                            <em>I</em>
                        </button>

                        <button
                            type="button"
                            onclick="formatText('underline')"
                        >
                            <u>U</u>
                        </button>

                        <button
                            type="button"
                            onclick="formatText('insertUnorderedList')"
                        >
                            • List
                        </button>

                        <button
                            type="button"
                            onclick="formatText('insertOrderedList')"
                        >
                            1. List
                        </button>

                    </div>


                    <div
                        id="blogEditor"
                        class="editor-area"
                        contenteditable="true"
                    ></div>


                    <input
                        type="hidden"
                        name="content"
                        id="blogContent"
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="image">
                    Blog Image
                    <span class="optional">
                        (Optional)
                    </span>
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <small class="input-help">
                    JPG, PNG or WebP. Maximum 5MB.
                </small>

            </div>


            <div class="form-actions">

                <button type="submit">
                    Publish Blog
                </button>

                <a
                    href="../dashboard/index.php"
                    class="button secondary-button"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</section>

<?php require_once "../includes/footer.php"; ?>