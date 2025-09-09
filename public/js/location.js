document.addEventListener("DOMContentLoaded", () => {
    const citySelect = document.querySelector("#location_city");
    const nameInput = document.querySelector("[data-autocomplete='location-name']");

    // autocomplete datalist
    const dataList = document.createElement("datalist");
    dataList.id = "location-suggestions";
    document.body.appendChild(dataList);

    // Relier le champ "name" au datalist
    nameInput.setAttribute("list", "location-suggestions");

    citySelect.addEventListener("change", () => {
        const cityId = citySelect.value;
        if (!cityId) return;

        fetch(`/api/locations/${cityId}`)
            .then(res => res.json())
            .then(data => {
                dataList.innerHTML = ""; // reset

                data.forEach(loc => {
                    const option = document.createElement("option");
                    option.value = loc.name; // affichage dans la liste
                    option.dataset.street = loc.street || "";
                    option.dataset.lat = loc.latitude;
                    option.dataset.lon = loc.longitude;
                    dataList.appendChild(option);
                });
            });
    });
});

