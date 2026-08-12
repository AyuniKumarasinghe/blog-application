document.addEventListener("DOMContentLoaded", function () {

    const deleteLinks = document.querySelectorAll(".delete-link");

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

});


/* =================================
   CHESSUPDATE BLOG EDITOR
   ================================= */

function formatText(command) {

    document.execCommand(command, false, null);

    document.getElementById("blogEditor").focus();
}


const blogForm = document.querySelector("form");

if (blogForm) {

    blogForm.addEventListener("submit", function () {

        const editor =
            document.getElementById("blogEditor");

        const content =
            document.getElementById("blogContent");

        if (editor && content) {

            content.value = editor.innerHTML;

        }

    });

}