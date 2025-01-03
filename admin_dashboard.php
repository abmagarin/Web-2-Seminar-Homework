<?php
session_start();
include 'data/db_connection.php';
require_once('admin_soap_service.php');

// Check if the admin is logged in
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$admin_username = $_SESSION['username'];
$adminService = new AdminSoapService($conn);

try {
    $dashboardData = $adminService->getAllDashboardData();

    // Extract data
    $notebooks = $dashboardData['notebooks'];
    $processors = $dashboardData['processors'];
    $operatingSystems = $dashboardData['operatingSystems'];
    $admins = $dashboardData['admins'];
    $users = $dashboardData['users'];
    $stats = $dashboardData['stats'];
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
        }
        .navbar, .sidebar {
            background-color: #1f1f1f;
        }
        .sidebar a {
            color: #ffffff;
        }
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            width: 250px;
        }
        .sidebar a:hover {
            background-color: #333333;
        }
        .table-dark th, .table-dark td {
            color: #ffffff;
        }
        .card {
            background-color: #1f1f1f;
        }
        .card-title {
            color: #ffffff;
        }
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="loadingOverlay" class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Welcome, <?php echo $admin_username; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary text-white" href="index.php">Back to Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" href="includes/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#Dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#laptops">Laptops</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#processors">Processors</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#opsystems">Operating Systems</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#admins">Admins</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#visitors">Visitors</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reference_codes">Reference codes</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 id="Dashboard" class="h2">Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Notebooks</h5>
                                <p class="card-text">Number: <?php echo $stats['total_notebooks']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Processors</h5>
                                <p class="card-text">Number: <?php echo $stats['total_processors']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Operating Systems</h5>
                                <p class="card-text">Number: <?php echo $stats['total_os']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Admins</h5>
                                <p class="card-text">Number: <?php echo $stats['total_admins']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-dark mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <p class="card-text">Number: <?php echo $stats['total_users']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laptops Table -->
                <div class="table-responsive">
                    <h2 id="laptops">Laptops</h2>
                    <button class="btn btn-success mb-3" id="add-notebook">Add New Notebook</button>
                    <table class="table table-dark table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Manufacturer</th>
                                <th>Type</th>
                                <th>Display</th>
                                <th>Memory</th>
                                <th>Harddisk</th>
                                <th>Videocontroller</th>
                                <th>Price</th>
                                <th>Processor ID</th>
                                <th>Operating System ID</th>
                                <th>Pieces</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="notebooks-body">
                            <?php foreach ($notebooks as $notebook): ?>
                            <tr>
                                <td><?php echo $notebook['id']; ?></td>
                                <td><?php echo $notebook['manufacturer']; ?></td>
                                <td><?php echo $notebook['type']; ?></td>
                                <td><?php echo $notebook['display']; ?></td>
                                <td><?php echo $notebook['memory']; ?></td>
                                <td><?php echo $notebook['harddisk']; ?></td>
                                <td><?php echo $notebook['videocontroller']; ?></td>
                                <td><?php echo $notebook['price']; ?></td>
                                <td><?php echo $notebook['processorid']; ?></td>
                                <td><?php echo $notebook['opsystemid']; ?></td>
                                <td><?php echo $notebook['pieces']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-notebook" data-id="<?php echo $notebook['id']; ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-notebook" data-id="<?php echo $notebook['id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div id="notebooks-pagination"></div>
                </div>

                <div class="table-responsive">
                    <h2 id="processors">Processors</h2>
                    <table class="table table-dark table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Manufacturer</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($processors as $processor): ?>
                            <tr>
                                <td><?php echo $processor['id']; ?></td>
                                <td><?php echo $processor['manufacturer']; ?></td>
                                <td><?php echo $processor['type']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <h2 id="opsystems">Operating Systems</h2>
                    <table class="table table-dark table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($operatingSystems as $os): ?>
                            <tr>
                                <td><?php echo $os['id']; ?></td>
                                <td><?php echo $os['name']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <h2 id="admins">Admins</h2>
                    <table class="table table-dark table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Reference Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?php echo $admin['id']; ?></td>
                                <td><?php echo $admin['username']; ?></td>
                                <td><?php echo $admin['reference_code']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Reference Codes Table -->
                <div class="table-responsive">
                    <h2 id="reference_codes">Reference Codes</h2>
                    <button class="btn btn-success mb-3" id="add-reference-code">Add New Reference Code</button>
                    <table class="table table-dark table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Used</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reference-codes-body">
                            <?php foreach ($dashboardData['referenceCodes'] as $code): ?>
                            <tr>
                                <td><?php echo $code['id']; ?></td>
                                <td><?php echo $code['code']; ?></td>
                                <td><?php echo $code['used'] ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-reference-code" data-id="<?php echo $code['id']; ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger delete-reference-code" data-id="<?php echo $code['id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <h2 id="visitors">Visitors</h2>
                    <table class="table table-dark table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('loadingOverlay').style.display = 'none';
        });

        window.addEventListener('beforeunload', function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    </script>
    <script>
    $(document).ready(function() {
    // Reference Code Actions
    $('#add-reference-code').click(function() {
        const code = prompt('Enter new reference code:');
        if (code) {
            $.ajax({
                url: 'admin_ajax_handler.php',
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    action: 'add_reference_code',
                    code: code
                }),
                success: function(response) {
                    console.log(response); // Debug log
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log detailed error
                    alert('An error occurred: ' + error);
                }
            });
        }
    });
// Edit Reference Code
$('.edit-reference-code').click(function() {
    const currentCode = $(this).closest('tr').find('td:nth-child(2)').text().trim();
    const newCode = prompt('Edit reference code:', currentCode);
    
    if (newCode && newCode.trim() !== currentCode) {
        $.ajax({
            url: 'admin_ajax_handler.php',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                action: 'edit_reference_code', 
                oldCode: currentCode,  // Send the current code
                newCode: newCode.trim()  // Send the new code, trimmed
            }),
            success: function(response) {
                console.log(response); // Debug log
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log detailed error
                alert('An error occurred: ' + error);
            }
        });
    }
});

// Delete Reference Code
$('.delete-reference-code').click(function() {
    const codeToDelete = $(this).closest('tr').find('td:nth-child(2)').text().trim();
    if (confirm('Are you sure you want to delete this reference code?')) {
        $.ajax({
            url: 'admin_ajax_handler.php',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                action: 'delete_reference_code', 
                code: codeToDelete  // Send the code to delete
            }),
            success: function(response) {
                console.log(response); // Debug log
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log detailed error
                alert('An error occurred: ' + error);
            }
        });
    }
});

    // Notebook Actions
    $('#add-notebook').click(function() {
        const notebookData = {
            manufacturer: prompt('Manufacturer:'),
            type: prompt('Type:'),
            display: parseFloat(prompt('Display Size:')),
            memory: parseInt(prompt('Memory (GB):')),
            harddisk: parseInt(prompt('Hard Disk (GB):')),
            videocontroller: prompt('Video Controller:'),
            price: parseFloat(prompt('Price:')),
            processorid: parseInt(prompt('Processor ID:')),
            opsystemid: parseInt(prompt('Operating System ID:')),
            pieces: parseInt(prompt('Number of Pieces:'))
        };

        $.ajax({
            url: 'admin_ajax_handler.php',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                action: 'add_notebook', 
                data: notebookData
            }),
            success: function(response) {
                console.log(response); // Debug log
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log detailed error
                alert('An error occurred: ' + error);
            }
        });
    });

    // Edit Notebook
    $('.edit-notebook').click(function() {
        const id = $(this).data('id');
        const $row = $(this).closest('tr');
        
        const notebookData = {
            manufacturer: prompt('Manufacturer:', $row.find('td:nth-child(2)').text()),
            type: prompt('Type:', $row.find('td:nth-child(3)').text()),
            display: parseFloat(prompt('Display Size:', $row.find('td:nth-child(4)').text())),
            memory: parseInt(prompt('Memory (GB):', $row.find('td:nth-child(5)').text())),
            harddisk: parseInt(prompt('Hard Disk (GB):', $row.find('td:nth-child(6)').text())),
            videocontroller: prompt('Video Controller:', $row.find('td:nth-child(7)').text()),
            price: parseFloat(prompt('Price:', $row.find('td:nth-child(8)').text())),
            processorid: parseInt(prompt('Processor ID:', $row.find('td:nth-child(9)').text())),
            opsystemid: parseInt(prompt('Operating System ID:', $row.find('td:nth-child(10)').text())),
            pieces: parseInt(prompt('Number of Pieces:', $row.find('td:nth-child(11)').text()))
        };

        $.ajax({
            url: 'admin_ajax_handler.php',
            method: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                action: 'edit_notebook', 
                id: id,
                data: notebookData
            }),
            success: function(response) {
                console.log(response); // Debug log
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log detailed error
                alert('An error occurred: ' + error);
            }
        });
    });

    // Delete Notebook
    $('.delete-notebook').click(function() {
        const id = $(this).data('id');
        if (confirm('Are you sure you want to delete this notebook?')) {
            $.ajax({
                url: 'admin_ajax_handler.php',
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    action: 'delete_notebook', 
                    id: id
                }),
                success: function(response) {
                    console.log(response); // Debug log
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); // Log detailed error
                    alert('An error occurred: ' + error);
                }
            });
        }
    });
});
</script>

</body>
</html>
