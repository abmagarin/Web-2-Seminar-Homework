<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Basket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .quantity-control {
            width: 80px;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,.075);
        }
        .price {
            font-weight: bold;
            color: #198754;
        }
        .empty-basket {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        .loading {
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="#">
            <img src="../images/logo.png" height="80" alt="Your Logo">
        </a>
        <div class="text-center flex-grow-1">
            <h1 class="navbar-text mb-0">My basket</h1>
        </div>
        <div class="navbar-nav ml-auto">
            <a href="../index.php" class="btn btn-outline-primary mr-2">
                <i class="fas fa-home mr-1"></i>Home
            </a>
            <a href="shop.php" class="btn btn-success">
                <i class="fas fa-shopping-cart mr-1"></i>Back to Shop
            </a>
        </div>
    </div>
</nav>


    <div class="container mt-5">
       
        <div id="basketItems" class="mt-4">
            <div class="loading">Loading your basket...</div>
        </div>
        <div class="mt-4 p-4 bg-light rounded shadow-sm border border-success">
    <h4 class="mb-3 text-dark">Total: <span id="totalPrice" class="price text-success">0</span> HUF</h4>
    <button id="generateInvoice" class="btn btn-success btn-lg" disabled>
        <i class="fas fa-file-invoice mr-2"></i>       Generate Invoice
    </button>
</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Load basket items when page loads
        $(document).ready(function() {
            loadBasket();
        });

        function loadBasket() {
    $.ajax({
        url: '../api/basket_api.php',
        method: 'GET',
        success: function(response) {
            let html = '';
            let total = 0;
            
            if (response && response.length > 0) {
                html = `
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price per unit</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                
                response.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;
                    
                    html += `
                        <tr data-id="${item.notebook_id}">
                            <td>
                                <strong>${item.manufacturer}</strong><br>
                                <small class="text-muted">${item.type}</small>
                            </td>
                            <td class="price">${item.price} HUF</td>
                            <td>
                                <input type="number" 
                                       class="form-control quantity-control" 
                                       min="1" 
                                       max="${item.stock_available + item.quantity}"
                                       value="${item.quantity}" 
                                       onchange="updateQuantity(${item.notebook_id}, this.value)">
                                <small class="text-muted">Stock: ${item.stock_available}</small>
                            </td>
                            <td class="price">${itemTotal} HUF</td>
                            <td>
                                <button class="btn btn-danger btn-sm" 
                                        onclick="removeItem(${item.notebook_id})">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                html += '</tbody></table>';
                $('#generateInvoice').prop('disabled', false);
            } else {
                html = `
                    <div class="empty-basket">
                        <h4>Your basket is empty</h4>
                        <p>Go back to the shop and add some items!</p>
                        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                    </div>
                `;
                $('#generateInvoice').prop('disabled', true);
            }
            
            $('#basketItems').html(html);
            $('#totalPrice').text(total.toLocaleString());
        },
        error: function(xhr, status, error) {
            $('#basketItems').html(`
                <div class="alert alert-danger">
                    Error loading basket: ${error}
                </div>
            `);
        }
    });
}

        function updateQuantity(notebookId, quantity) {
            $.ajax({
                url: '../api/basket_api.php',
                method: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify({
                    notebook_id: notebookId,
                    quantity: quantity
                }),
                success: function(response) {
                    loadBasket();
                    showToast('Quantity updated successfully');
                },
                error: function(xhr, status, error) {
                    showToast('Error updating quantity', 'error');
                }
            });
        }

        function removeItem(notebookId) {
            if (confirm('Are you sure you want to remove this item?')) {
                $.ajax({
                    url: `../api/basket_api.php?notebook_id=${notebookId}`,
                    method: 'DELETE',
                    success: function(response) {
                        loadBasket();
                        showToast('Item removed from basket');
                    },
                    error: function(xhr, status, error) {
                        showToast('Error removing item', 'error');
                    }
                });
            }
        }

        function showToast(message, type = 'success') {
            // Create toast element
            const toast = $(`
                <div class="toast position-fixed bottom-0 end-0 m-3" role="alert">
                    <div class="toast-header bg-${type === 'success' ? 'success' : 'danger'} text-white">
                        <strong class="me-auto">Basket Update</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `);
            
            // Add to document
            $('body').append(toast);
            
            // Show and then remove
            toast.toast('show');
            setTimeout(() => toast.remove(), 3000);
        }

        // Handle invoice generation
        $('#generateInvoice').click(function() {
            window.location.href = 'generate_invoice.php';
        });
    </script>
    
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>