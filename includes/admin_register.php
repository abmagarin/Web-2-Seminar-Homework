<?php
include '../data/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Take the variables from the HTTP request and store them in PHP variables
    $username = $_POST['username'];
    $password = $_POST['password'];
    $reference_code = $_POST['reference_code'];

    // Check if username already exists in the database
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the username exists, return an error message
        echo "Username already taken.";
    } else {
        // Check if the reference code is valid
        $stmt = $conn->prepare("SELECT * FROM reference_codes WHERE code = ? AND used = 0");
        $stmt->bind_param("s", $reference_code);
        $stmt->execute();
        $codeResult = $stmt->get_result();

        if ($codeResult->num_rows === 0) {
            echo "Invalid or already used reference code.";
        } else {
            // Hash the password before storing it
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new admin into the database
            $stmt = $conn->prepare("INSERT INTO admins (username, password, reference_code) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $reference_code);

            if ($stmt->execute()) {
                // Mark the reference code as used
                $stmt = $conn->prepare("UPDATE reference_codes SET used = 1 WHERE code = ?");
                $stmt->bind_param("s", $reference_code);
                $stmt->execute();

                // Log the user in and set the session variables
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['is_admin'] = true;  // Set as admin

                echo "success";  // Return success if the admin is successfully registered and logged in
            } else {
                echo "Error occurred. Please try again.";
            }
        }
    }
}
?>
