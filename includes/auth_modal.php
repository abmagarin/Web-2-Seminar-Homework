
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- User or Admin Login Selection -->
                <div class="d-flex justify-content-between mb-3">
                    <button class="btn btn-outline-primary" id="userBtn">User</button>
                    <button class="btn btn-outline-secondary" id="adminBtn">Admin</button>
                </div>

                <!-- Error message -->
                <div id="error-message" style="display:none;" class="alert alert-danger"></div>

                <!-- User Login Form -->
                <form id="loginForm" class="login-form">
                    <input type="text" name="username" id="username" placeholder="Enter your username" required class="form-control mb-3">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <!-- Admin Login Form (Hidden by default) -->
                <form id="adminLoginForm" class="login-form" style="display: none;">
                    <input type="text" name="admin_username" id="adminUsername" placeholder="Enter admin username" required class="form-control mb-3">
                    <input type="password" name="admin_password" id="adminPassword" placeholder="Enter admin password" required class="form-control mb-3">
                    <button type="submit" class="btn btn-danger w-100">Admin Login</button>
                </form>

                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="#" id="registerLink">Register HERE</a></p>
                    <p>Want to be an admin? <a href="#" id="adminRegisterLink">Register as Admin</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script> // JavaScript to toggle between user and admin login
document.getElementById('userBtn').addEventListener('click', function() {
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('adminLoginForm').style.display = 'none';
    document.getElementById('authModalLabel').innerText = "User Login";
});

document.getElementById('adminBtn').addEventListener('click', function() {
    document.getElementById('adminLoginForm').style.display = 'block';
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('authModalLabel').innerText = "Admin Login";
});

// Default to User login form on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('adminLoginForm').style.display = 'none';
});
</script>
 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   // User login submission
$('#loginForm').on('submit', function(event) {
    event.preventDefault();  // Prevent page reload

    // Get form values
    var username = $('#username').val();
    var password = $('#password').val();

    // Perform AJAX request for user login
    $.ajax({
        url: 'includes/login.php',  // URL of the PHP file handling the login
        type: 'POST',
        data: {
            username: username,
            password: password
        },
        success: function(response) {
            if (response === 'success') {
                window.location.href = 'index.php';  // Redirect after successful login
                alert('Login successful!');
            } else {
                // Show error message if the user is not found
                $('#error-message').text(response).show();
            }
        },
        error: function() {
            $('#error-message').text('An error occurred. Please try again.').show();
        }
    });
});

// Admin login submission
$('#adminLoginForm').on('submit', function(event) {
    event.preventDefault();  // Prevent page reload

    // Get form values
    var adminUsername = $('#adminUsername').val();
    var adminPassword = $('#adminPassword').val();

    // Perform AJAX request for admin login
    $.ajax({
        url: 'includes/admin_login.php',  // URL of the PHP file handling the admin login
        type: 'POST',
        data: {
            admin_username: adminUsername,
            admin_password: adminPassword
        },
        success: function(response) {
            if (response === 'success') {
                window.location.href = 'admin_dashboard.php';  // Redirect to admin dashboard
                alert('Admin login successful!');
            } else {
                // Show error message if admin login fails
                $('#error-message').text(response).show();
            }
        },
        error: function() {
            $('#error-message').text('An error occurred. Please try again.').show();
        }
    });
});

</script>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Sign Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="registerForm"> <!-- Use an ID to select the form for AJAX -->
                    <input type="text" name="username" id="registerUsername" placeholder="Enter your username" required class="form-control mb-3">
                    <input type="password" name="password" id="registerPassword" placeholder="Enter your password" required class="form-control mb-3">
                    <input type="password" name="confirm_password" id="registerConfirmPassword" placeholder="Confirm your password" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <div id="registerError" style="color: red; display: none; margin-top: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#registerForm').submit(function(event) {
        event.preventDefault();  // Prevent the form from submitting normally

        var username = $('#registerUsername').val();
        var password = $('#registerPassword').val();
        var confirmPassword = $('#registerConfirmPassword').val();

        // Check if passwords match
        if (password !== confirmPassword) {
            $('#registerError').text('Passwords do not match!').show();
            return;
        }

        $.ajax({
            url: 'includes/register.php',  // URL to the PHP file handling registration
            type: 'POST',
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                if (response === 'success') {
                    // Redirect or show success message if registration is successful
                    alert('Registration successful!');
                    window.location.href = 'index.php';
                } else {
                    // Show error message if registration fails
                    $('#registerError').text(response).show();
                }
            },
            error: function() {
                $('#registerError').text('An error occurred. Please try again.').show();
            }
        });
    });
</script>

<script>
    document.getElementById('registerLink').addEventListener('click', function (event) {
        // Close the login modal
        var loginModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
        loginModal.hide();

        // Open the register modal
        var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
        registerModal.show();
    });
</script>
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true" 
    <?php if (!empty($error_message)) echo 'style="display:block; opacity:1;"'; ?>>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">Sign In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Error message -->
                <div id="error-message" style="display:none;" class="alert alert-danger"></div>
                <!-- Login form -->
                <form id="loginForm">
                    <input type="text" name="username" id="username" placeholder="Enter your username" required class="form-control mb-3">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="#" id="registerLink">Register HERE</a></p>
                    <p>Want to be an admin? <a href="#" id="adminRegisterLink">Register as Admin</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="adminRegisterModal" tabindex="-1" aria-labelledby="adminRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminRegisterModalLabel">Admin Sign Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="adminRegisterForm">
                    <input type="text" name="username" id="adminRegisterUsername" placeholder="Enter your username" required class="form-control mb-3">
                    <input type="password" name="password" id="adminRegisterPassword" placeholder="Enter your password" required class="form-control mb-3">
                    <input type="password" name="confirm_password" id="adminRegisterConfirmPassword" placeholder="Confirm your password" required class="form-control mb-3">
                    <input type="text" name="reference_code" id="adminRegisterReferenceCode" placeholder="Enter your reference code" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <div id="adminRegisterError" style="color: red; display: none; margin-top: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#adminRegisterForm').submit(function(event) {
        event.preventDefault();  // Prevent the form from submitting normally

        var username = $('#adminRegisterUsername').val();
        var password = $('#adminRegisterPassword').val();
        var confirmPassword = $('#adminRegisterConfirmPassword').val();
        var referenceCode = $('#adminRegisterReferenceCode').val();

        // Check if passwords match
        if (password !== confirmPassword) {
            $('#adminRegisterError').text('Passwords do not match!').show();
            return;
        }

        $.ajax({
            url: 'includes/admin_register.php',  // URL to the PHP file handling admin registration
            type: 'POST',
            data: {
                username: username,
                password: password,
                reference_code: referenceCode
            },
            success: function(response) {
                if (response === 'success') {
                    // Redirect or show success message if registration is successful
                    alert('Admin Registration successful!');
                    window.location.href = 'index.php';
                } else {
                    // Show error message if registration fails
                    $('#adminRegisterError').text(response).show();
                }
            },
            error: function() {
                $('#adminRegisterError').text('An error occurred. Please try again.').show();
            }
        });
    });

    document.getElementById('adminRegisterLink').addEventListener('click', function (event) {
        // Close the login modal
        var loginModal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
        loginModal.hide();

        // Open the admin register modal
        var adminRegisterModal = new bootstrap.Modal(document.getElementById('adminRegisterModal'));
        adminRegisterModal.show();
    });
</script>
