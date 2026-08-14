document.addEventListener("DOMContentLoaded", function () {

    /*
     * Delete confirmation
     */

    const deleteLinks =
        document.querySelectorAll(".delete-link");

    deleteLinks.forEach(function (link) {

        link.addEventListener("click", function (event) {

            const confirmed = confirm(
                "Are you sure you want to delete this blog?"
            );

            if (!confirmed) {
                event.preventDefault();
            }

        });

    });


    /*
     * Blog editor
     */

    const editor =
        document.getElementById("blogEditor");

    const content =
        document.getElementById("blogContent");

    const form =
        editor ? editor.closest("form") : null;


    if (editor && content && form) {

        form.addEventListener(
            "submit",
            function () {

                content.value =
                    editor.innerHTML;

            }
        );


        /*
         * Load existing content for Edit page
         */

        if (editor.innerHTML.trim() !== "") {

            content.value =
                editor.innerHTML;

        }
    }

});


/*
 * Rich text formatting
 */

function formatText(command) {

    const editor =
        document.getElementById("blogEditor");

    if (!editor) {
        return;
    }

    editor.focus();

    document.execCommand(
        command,
        false,
        null
    );
}