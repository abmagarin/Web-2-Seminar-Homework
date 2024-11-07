<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true" 
    <?php if (!empty($error_message)) echo 'style="display:block; opacity:1;"'; ?>>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">Sign In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                if (!empty($error_message)) {
                    echo "<div class='alert alert-danger'>$error_message</div>";
                }
                ?>
                <form method="POST" action="">
                    <input type="text" name="username" placeholder="Enter your username" required class="form-control mb-3">
                    <input type="password" name="password" placeholder="Enter your password" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
                <div class="mt-3 text-center">
                    <p>Don't have an account? <a href="#" id="registerLink">Register HERE</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">Sign Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="index.php"> <!-- Replace with your registration logic -->
                    <input type="text" name="username" placeholder="Enter your username" required class="form-control mb-3">
                    <input type="password" name="password" placeholder="Enter your password" required class="form-control mb-3">
                    <input type="password" name="confirm_password" placeholder="Confirm your password" required class="form-control mb-3">
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
