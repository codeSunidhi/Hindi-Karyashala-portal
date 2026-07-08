// ===============================
// Login Form Validation
// ===============================

function validateForm() {

    let ic = document.getElementById("ic_no").value.trim();
    let password = document.getElementById("password").value.trim();
    let role = document.getElementById("role").value;
    let error = document.getElementById("error");

    error.innerHTML = "";

    // IC Number
    if (ic === "") {
        error.innerHTML = "Please enter IC Number.";
        return false;
    }

    if (!/^\d+$/.test(ic)) {
        error.innerHTML = "IC Number should contain only digits.";
        return false;
    }

    if (ic.length !== 4) {
        error.innerHTML = "IC Number should be 4 digits (1001,1002...).";
        return false;
    }

    // Password
    if (password === "") {
        error.innerHTML = "Please enter Password.";
        return false;
    }

    if (password.length < 6) {
        error.innerHTML = "Password should contain at least 6 characters.";
        return false;
    }

    // Role
    if (role === "") {
        error.innerHTML = "Please select Login Role.";
        return false;
    }

    return true;
}


// =======================================
// Search Employee Table
// =======================================

function searchTable() {

    let input = document.getElementById("search");
    let filter = input.value.toUpperCase();

    let table = document.getElementById("employeeTable");

    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {

        let td = tr[i].getElementsByTagName("td")[1];

        if (td) {

            let txtValue = td.textContent || td.innerText;

            if (txtValue.toUpperCase().indexOf(filter) > -1) {

                tr[i].style.display = "";

            } else {

                tr[i].style.display = "none";

            }

        }

    }

}


// =======================================
// Open Modal
// =======================================

function openModal() {

    document.getElementById("editModal").style.display = "block";

}


// =======================================
// Close Modal
// =======================================

function closeModal() {

    document.getElementById("editModal").style.display = "none";

}


// =======================================
// Close Modal if clicked outside
// =======================================

window.onclick = function(event) {

    let modal = document.getElementById("editModal");

    if (event.target == modal) {

        modal.style.display = "none";

    }

}