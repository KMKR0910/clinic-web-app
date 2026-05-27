// Wait until page loads
document.addEventListener("DOMContentLoaded", function () {

    // Form validation
    const form = document.querySelector("form");

    form.addEventListener("submit", function (e) {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        if (email === "" || password === "") {
            alert("Please fill in all fields.");
            e.preventDefault();
            return;
        }

        // simple email check
        const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/i;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email.");
            e.preventDefault();
            return;
        }

        // disable button after valid submit
        const loginBtn = document.querySelector(".login-button");
        loginBtn.disabled = true;
        loginBtn.innerText = "Logging in...";
    });

});


// Show / Hide password
function togglePasswordVisibility() {
    const passwordField = document.getElementById("password");
    const toggleText = document.querySelector(".toggle-password");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        toggleText.innerHTML = "<u>Hide</u>";
    } else {
        passwordField.type = "password";
        toggleText.innerHTML = "<u>Show</u>";
    }
}