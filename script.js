document.addEventListener("DOMContentLoaded", function() {
    var loginButton = document.getElementById("loginButton");
    var logoutButton = document.getElementById("logoutButton");
    var loginModal = document.getElementById("loginModal");
    var span = document.getElementsByClassName("close")[0];
    var registerLink = document.getElementById("registerLink");
    var registerHeading = document.getElementById("registerHeading");
    var loginForm = document.getElementById("loginForm");
    var registerForm = document.getElementById("registerForm");

    loginButton.onclick = function() {
        loginModal.style.display = "block";
    }

    span.onclick = function() {
        loginModal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == loginModal) {
            loginModal.style.display = "none";
        }
    }

    registerLink.onclick = function(event) {
        event.preventDefault();
        loginForm.style.display = "none";
        registerLink.style.display = "none";
        registerHeading.style.display = "block";
        registerForm.style.display = "block";
    }

    if (logoutButton) {
        logoutButton.onclick = function() {
            window.location.href = 'logout.php';
        }
    }
});
