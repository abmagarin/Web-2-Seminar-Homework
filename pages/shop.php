<?php
session_start();
require_once '../data/db_connection.php'; //  
 
// Fetch notebooks from the database
try {
    $dbh = new PDO('mysql:host=localhost;dbname=laptop', 'root', '',
                   array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    $stmt = $dbh->prepare("SELECT * FROM notebook");
    $stmt->execute();
    $notebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laptop Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .laptop-card {
            transition: transform 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .laptop-card:hover {
            transform: scale(1.05);
        }
         
        .laptop-details {
            position: absolute;
            top: -180%;
            width: 84%;
            background: rgba(0,0,0,0.9);
            color: white;
            padding: 40px;
            transition: top 0.3s ease;
        }
        .laptop-card:hover .laptop-details {
            top: 0;
        }
        .add-to-basket {
            position: absolute;
            width:60px;
            height: 250px;
            top: 1px;
            right: 3px;
            z-index: 10;
        }
        .pagination {
         margin-bottom: 50px;
        }

        .page-link {
            color: #0B3E17FF; /* Green color to match your theme */
            border-color: #74C485FF;
        }

        .page-item.active .page-link {
            background-color: #2872A7FF;
            border-color: #058C29FF;
            color: white;
        }

        .page-link:hover {
            color: #218838;
            background-color: #e9ecef;
            border-color: #28a745;
        }
        .price-container {
            text-align: center;
            padding: 10px 0;
            margin: 10px 0;
            background: rgba(220, 53, 69, 0.1); /* Light red background */
            border-radius: 8px;
        }

        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: #dc3545 !important; /* Red color */
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            position: relative;
            display: inline-block;
        }

        .price-tag::before {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            height: 2px;
            background-color: #dc3545;
        }

        /* Optional: Add hover effect */
        .price-container:hover .price-tag {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }

        /* Optional: Add animation when the page loads */
        @keyframes priceAppear {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .price-tag {
            animation: priceAppear 0.5s ease-out forwards;
        }
        .page-item.disabled .page-link {
            color: #6c757d;
            border-color: #dee2e6;
        }
        .manufacturer-placeholder {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php
// At the top of your PHP file
$itemsPerPage = 30; // Number of laptops per page
$totalItems = count($notebooks); // Total number of laptops
$totalPages = ceil($totalItems / $itemsPerPage);
$currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Slice the notebooks array to get only the items for the current page
$notebooksOnPage = array_slice($notebooks, $offset, $itemsPerPage);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="#">
            <img src="../images/logo.png" height="80" alt="Your Logo">
        </a>
        <div class="text-center flex-grow-1">
            <h1 class="navbar-text mb-0">Our Laptop Collection</h1>
        </div>
        <div class="navbar-nav ml-auto">
            <a href="../index.php" class="btn btn-outline-primary mr-2">
                <i class="fas fa-home mr-1"></i>Home
            </a>
             
        </div>
    </div>
</nav>


<div class="container mt-5">
    
    <div class="row">
        <?php foreach ($notebooksOnPage as $notebook): ?>
            <div class="col-md-4 mb-4">
                <div class="card laptop-card" data-id="<?= $notebook['id'] ?>">
                    <div class="button-container">
                        <button class="btn btn-success add-to-basket" data-id="<?= $notebook['id'] ?>">
                            <i class="fas fa-plus">Buy now</i>
                        </button>
                    </div>
                    <?php
                    $manufacturerColors = [
                        'HP' => '#AC7272FF', // Soft red
                        'LENOVO' => '#A9AC72FF', // Soft blue
                        'ThinkPad' => '#9872ACFF', // Soft teal
                        'ACER' => '#7295ACFF', // Soft purple
                        'Asus' => '#150922FF', // Soft orange
                        'TOSHIBA' => '#A10000FF', // Soft mint green
                        'DELL' => '#DB8A8AFF', // Soft pink
                        'ASUS' => '#AC7272FF', // Soft sky blue
                        'Samsung' => '#DA90DEFF', // Soft yellow
                        'FUJITSU' => '#AB6969FF', // Soft lavender
                        'MSI' => '#7972ACFF' // Soft seafoam green
                    ];

                    $defaultColor = 'rgba(1, 1, 1, 1)'; // Default color
                    $manufacturer = $notebook['manufacturer'];
                    $backgroundColor = isset($manufacturerColors[$manufacturer]) ? $manufacturerColors[$manufacturer] : $defaultColor;
                    ?>
                    <div class="manufacturer-placeholder" style="background-color: <?= $backgroundColor ?>; height: 250px; width: 350px; display: flex; justify-content: center; align-items: center; font-size: 24px; font-weight: bold; color: #ffffff;">
                        <?= $manufacturer ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $notebook['manufacturer'] ?> - <?= $notebook['type'] ?></h5>
                                            <!-- Replace the existing price line with this: -->
                    <div class="price-container">
                        <p class="card-text price-tag">Price: <?= number_format($notebook['price'], 0) ?> HUF</p>
                    </div>
                    </div>
                    <div class="laptop-details">
                        <h6>Detailed Specifications:</h6>
                        <ul class="list-unstyled">
                            <li>Display: <?= $notebook['display'] ?> inches</li>
                            <li>Memory: <?= $notebook['memory'] ?> GB</li>
                            <li>Hard Disk: <?= $notebook['harddisk'] ?> GB</li>
                            <li>Video Controller: <?= $notebook['videocontroller'] ?></li>
                            <li>Available: <?= $notebook['pieces'] ?> in stock</li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <!-- Previous button -->
                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Previous</span>
                        </a>
                    </li>

                    <!-- Page numbers -->
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);

                    if ($startPage > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                        if ($startPage > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $startPage; $i <= $endPage; $i++) {
                        echo '<li class="page-item ' . ($currentPage == $i ? 'active' : '') . '">
                                <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                              </li>';
                    }

                    if ($endPage < $totalPages) {
                        if ($endPage < $totalPages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
                    }
                    ?>

                    <!-- Next button -->
                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">Next &raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
    $('.add-to-basket').on('click', function(e) {
        e.stopPropagation();
        const notebookId = $(this).data('id');
        const $addButton = $(this);
        
        // Create notification container if not exists
        if ($('#notification-container').length === 0) {
            $('body').append('<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 1000;"></div>');
        }
        
        $.ajax({
            url: '../includes/add_to_basket.php',
            method: 'POST',
            data: {
                notebook_id: notebookId,
                quantity: 1
            },
            success: function(response) {
                // Green success notification
                const $notification = $(`
                    <div class="alert alert-success" style="
                        background-color: #dff0d8; 
                        color: #3c763d; 
                        border: 1px solid #d6e9c6; 
                        padding: 15px; 
                        margin-bottom: 10px; 
                        border-radius: 4px;
                    ">
                        ${response}
                    </div>
                `);
                
                $('#notification-container').append($notification);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    $notification.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
                
                // Optional: Briefly highlight the add to basket button
                $addButton.addClass('btn-success');
                setTimeout(() => {
                    $addButton.removeClass('btn-success');
                }, 1000);
            },
            error: function() {
                // Red error notification
                const $errorNotification = $(`
                    <div class="alert alert-danger" style="
                        background-color: #f2dede; 
                        color: #a94442; 
                        border: 1px solid #ebccd1; 
                        padding: 15px; 
                        margin-bottom: 10px; 
                        border-radius: 4px;
                    ">
                        Error adding to basket
                    </div>
                `);
                
                $('#notification-container').append($errorNotification);
                
                // Auto-remove after 3 seconds
                setTimeout(() => {
                    $errorNotification.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    });
});
    </script>
</body>
</html>