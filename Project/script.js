document.addEventListener('DOMContentLoaded', function() {
    // Password validation
    const passwordValidation = (password) => {
        const minLength = 8;
        const hasLetter = /[A-Za-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[@$!%*#?&]/.test(password);
        return password.length >= minLength && hasLetter && hasNumber;
    };

    // Email validation
    const emailValidation = (email) => {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    };

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = form.querySelector('input[type="password"]');
            const email = form.querySelector('input[type="email"]');
            
            if (email && !emailValidation(email.value)) {
                alert('Please enter a valid email address');
                return;
            }
            
            if (password && !passwordValidation(password.value)) {
                alert('Password must be at least 8 characters long and contain letters and numbers ');
                return;
            }
            
            // If validation passes, submit the form
            form.submit();
        });
    });

    // Password confirmation check
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
            }
        });
    }


     
    
    document.addEventListener('DOMContentLoaded', function() {
        // Handle successful login
        function handleSuccessfulAuth(response) {
            if (response.success) {
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('authModal'));
                modal.hide();
                
                // Reload the page to update the navigation
                window.location.reload();
            } else {
                alert(response.message || 'Authentication failed');
            }
        }
    
        // Handle logout
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('includes/logout.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
            });
        }
    
        // Update form submission handlers to use fetch
        const forms = document.querySelectorAll('#loginForm, #registerForm, #adminForm');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (this.checkValidity()) {
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(response => response.json())
                    .then(handleSuccessfulAuth)
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
     // Add this to your existing JavaScript or replace the form handling section
document.addEventListener('DOMContentLoaded', function() {
    // Function to show error/success message
    function showMessage(message, isError = false) {
        // Remove any existing message
        const existingMessage = document.querySelector('.alert');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message element
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('alert', isError ? 'alert-danger' : 'alert-success', 'mt-3');
        messageDiv.role = 'alert';
        messageDiv.textContent = message;
        
        // Add the message before the form
        const form = document.querySelector('.tab-pane.active form');
        form.insertAdjacentElement('beforebegin', messageDiv);
        
        // Auto-hide message after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
    
    // Handle form submissions
    const forms = document.querySelectorAll('#loginForm, #registerForm, #adminForm');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (this.checkValidity()) {
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message || 'Success!', false);
                        
                        // If registration/login successful, reload page after brief delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showMessage(data.message || 'An error occurred.', true);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An unexpected error occurred. Please try again.', true);
                });
            } else {
                showMessage('Please fill out all required fields correctly.', true);
            }
        });
    });
    
    // Clear messages when switching tabs
    const tabButtons = document.querySelectorAll('[data-bs-toggle="pill"]');
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const existingMessage = document.querySelector('.alert');
            if (existingMessage) {
                existingMessage.remove();
            }
        });
    });
});



});
 