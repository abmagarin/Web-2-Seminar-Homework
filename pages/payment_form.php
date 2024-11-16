<?php
session_start();
// Ensure user is logged in and has items in basket
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .payment-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-container">
            <h2 class="text-center mb-4">Payment Details</h2>
            <form id="paymentForm">
                <div class="mb-3">
                    <label for="cardName" class="form-label">Card Holder Name</label>
                    <input type="text" class="form-control" id="cardName" required>
                </div>
                <div class="mb-3">
                    <label for="cardNumber" class="form-label">Card Number</label>
                    <input type="text" class="form-control" id="cardNumber" 
                           pattern="[0-9]{16}" 
                           title="16 digit card number" 
                           maxlength="16" 
                           required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expiryDate" class="form-label">Expiry Date</label>
                        <input type="month" class="form-control" id="expiryDate" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" 
                               pattern="[0-9]{3}" 
                               title="3 digit CVV" 
                               maxlength="3" 
                               required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Complete Payment</button>
                
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically send the payment details to a payment processor
            alert('As this project is used to study purposes, Dont worry your data is not saved or used by 3rd party )');
            // Redirect to invoice generation
            window.location.href = '../includes/pdf_generator.php';
        });
    </script>
</body>
</html>