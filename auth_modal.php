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
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Action when the form is submitted
    $('#loginForm').on('submit', function(event) {
        event.preventDefault();  // Prevent page reload

        // Get form values
        var username = $('#username').val();
        var password = $('#password').val();

        // Perform AJAX request
        $.ajax({
            url: 'login.php',  // URL of the PHP file handling the login
            type: 'POST',
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                if (response === 'success') {
                    window.location.href = 'index.php';
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
            url: 'register.php',  // URL to the PHP file handling registration
            type: 'POST',
            data: {
                username: username,
                password: password
            },
            success: function(response) {
                if (response === 'success') {
                    // Redirect or show success message if registration is successful
                    alert('Registration successful!');
                    window.location.href = 'index.php';  // Redirect to index.php on success
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
