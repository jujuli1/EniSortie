document.addEventListener("DOMContentLoaded", () => {
    // Select the search form element
    const form = document.getElementById("search-form");
    // Select the form button
    //const formBtn = document.getElementById("btn-search");

    // Listen to any change in the form fields
    form.addEventListener("submit", function (e) {
        // prevent the default submit
        e.preventDefault();

        // Collect form data
        const formData = new FormData(e.target);

        // Send form data via AJAX to the form's action URL
        fetch(this.action, {
            method: "POST", // Use POST method
            body: formData // Attach the form data
        })
            .then(response => response.text()) // Convert response to plain HTML
            .then(html => {
                // Replace the content of the outing table with the new filtered results
                document.getElementById("outing-table").innerHTML =
                    new DOMParser().parseFromString(html, "text/html")
                        .getElementById("outing-table").innerHTML;
            });
    });
});

