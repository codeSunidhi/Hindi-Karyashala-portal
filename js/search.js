document.addEventListener("DOMContentLoaded", function () {

    const searchBox = document.getElementById("searchBox");

    if (!searchBox) {
        return;
    }

    searchBox.addEventListener("keyup", function () {

        let filter = this.value.toLowerCase().trim();

        let table = document.getElementById("employeeTable");

        if (!table) {
            return;
        }

        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {

            let row = rows[i];

            let text = row.textContent.toLowerCase();

            if (text.includes(filter)) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        }

    });

});