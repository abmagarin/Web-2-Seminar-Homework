<?php
require_once 'includes/db_connect.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    // Fetch the admin's information from the database
    $adminId = $_SESSION['admin_id'];
    $stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
    $stmt->bind_param("i", $adminId);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Display the admin's information on the admin dashboard
    echo "Welcome, " . $admin['username'] . "!";
    echo "<br>";
    echo "Access Code: " . $admin['access_code'];

    // Add the logout button to the admin dashboard
    echo "<br>";
    echo "<a href='#' id='logoutBtn'>Logout</a>";

    // JavaScript code for the logout button
    ?>
    <script>
        const logoutBtn = document.getElementById('logoutBtn');
        logoutBtn.addEventListener('click', () => {
            // Send an AJAX request to the logout_process.php file
            fetch('includes/logout_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect the admin to the login page or any other appropriate page
                    window.location.href = 'index.php';
                } else {
                    alert('Error logging out. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    </script>
    <?php
} else {
    // Redirect the user to the authentication modal or the login page
    header("Location: index.php");
    exit;
}

$stmt->close();
$conn->close();
?>