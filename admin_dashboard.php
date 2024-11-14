<?php
session_start();
include 'data/db_connection.php';  // Adjust this path as per your structure

// Check if the admin is logged in
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

$admin_username = $_SESSION['username'];
?>

<?php
 $total_sales_query = "SELECT COUNT(*) FROM notebook"; 
 $total_laptops_query = "SELECT COUNT(*) FROM notebook"; // Table name updated to 'notebook'
 $total_processors_query = "SELECT COUNT(*) FROM processor";
 $total_os_query = "SELECT COUNT(*) FROM opsystem";
 $total_admin_query = "SELECT COUNT(*) FROM admins ";
 $total_visitors_query = "SELECT COUNT(*) FROM users";
 
 // Get total sales
$total_sales_result = $conn->query($total_sales_query);
$total_sales = $total_sales_result ? $total_sales_result->fetch_row()[0] : 0;  // Default to 0 if no result
 
 // Get total laptops
 $total_laptops_result = $conn->query($total_laptops_query);
 $total_laptops = $total_laptops_result->fetch_row()[0];
 
 // Get total processors
 $total_processors_result = $conn->query($total_processors_query);
 $total_processors = $total_processors_result->fetch_row()[0];
 
 // Get total operating systems
 $total_os_result = $conn->query($total_os_query);
 $total_os = $total_os_result->fetch_row()[0];
 
 // Get total admins
 $total_admin_result = $conn->query($total_admin_query);  
 $total_admins = $total_admin_result->fetch_row()[0];
 
 // Get total visitors
 $total_visitors_result = $conn->query($total_visitors_query);
 $total_visitors = $total_visitors_result->fetch_row()[0];
 
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
                top: 0; /* Adjust this value as needed */
                height: 100vh; /* Ensures it stays within the height of the viewport */
                width: 250px; /* Adjust width as necessary */
                 
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Welcome, Dear Administrator <?php echo $admin_username; ?></a>
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
                            <a class="nav-link active" href="#sales">Sales</a>
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
                <h5 class="card-title">Total Sales</h5>
                <p class="card-text">Number: <?php echo $total_sales; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Laptops</h5>
                <p class="card-text">Number: <?php echo $total_laptops; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Processors</h5>
                <p class="card-text">Number: <?php echo $total_processors; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Operating Systems</h5>
                <p class="card-text">Number: <?php echo $total_os; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Admins</h5>
                <p class="card-text">Number: <?php echo $total_admins; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-dark mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Visitors</h5>
                <p class="card-text">Number: <?php echo $total_visitors; ?></p>
            </div>
        </div>
    </div>
</div>

                <div class="table-responsive">
                    <h2 id="sales">Sales</h2>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM notebook");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['manufacturer']}</td>
                                        <td>{$row['type']}</td>
                                        <td>{$row['display']}</td>
                                        <td>{$row['memory']}</td>
                                        <td>{$row['harddisk']}</td>
                                        <td>{$row['videocontroller']}</td>
                                        <td>{$row['price']}</td>
                                        <td>{$row['processorid']}</td>
                                        <td>{$row['opsystemid']}</td>
                                        <td>{$row['pieces']}</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <h2 id="laptops">Laptops</h2>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM notebook");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['manufacturer']}</td>
                                        <td>{$row['type']}</td>
                                        <td>{$row['display']}</td>
                                        <td>{$row['memory']}</td>
                                        <td>{$row['harddisk']}</td>
                                        <td>{$row['videocontroller']}</td>
                                        <td>{$row['price']}</td>
                                        <td>{$row['processorid']}</td>
                                        <td>{$row['opsystemid']}</td>
                                        <td>{$row['pieces']}</td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <h2 id="processors">Processors</h2>
                    <table class="table table-dark table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM processor");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['manufacturer']}</td>
                                        <td>{$row['type']}</td>
                                      </tr>";
                            }
                            ?>
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
                            <?php
                            $result = $conn->query("SELECT * FROM opsystem");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['name']}</td>
                                      </tr>";
                            }
                            ?>
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
                            <?php
                            $result = $conn->query("SELECT * FROM admins");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['username']}</td>
                                        <td>{$row['reference_code']}</td>
                                      </tr>";
                            }
                            ?>
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
                            <?php
                            $result = $conn->query("SELECT * FROM users");
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['username']}</td>
                                         
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
