document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("search-form");

    form.addEventListener("change", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(html => {
                document.getElementById("outing-table").innerHTML =
                    new DOMParser().parseFromString(html, "text/html")
                        .getElementById("outing-table").innerHTML;
            });
    });
});

