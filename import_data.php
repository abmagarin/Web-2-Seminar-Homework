<?php
// Display errors in case something goes wrong
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost"; // Change if necessary
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$dbname = "Laptop";        // Name of your database

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Failed connection: " . $conn->connect_error);
}

echo "///////////////////////////<br>////NOTEBOOK////<br>///////////////////////////<br>";

// Read the text file
$file = __DIR__ . '/images/notebook.txt'; // Relative path to the text file

// Check if the file exists
if (!file_exists($file)) {
    die("The file was not found.");
}

// Read the content of the file as a string
$data = file_get_contents($file);

// Split the content of the file into lines (one for each record)
$rows = explode(PHP_EOL, $data);

// Remove the first row (headers)
array_shift($rows);
$number_of_row = 0;

// Insert each row into the database
foreach ($rows as $row) {
    // Split the row by tabs
    $columns = explode("\t", $row);

    // Ensure there are no empty rows
    if (count($columns) > 1) {
        $id = $number_of_row;
        $manufacturer = $conn->real_escape_string($columns[0]); // Escape quotes for security
        $type = $conn->real_escape_string($columns[1]);
        $display = (float)$columns[2]; // Convert the price to float
        $memory = (int)$columns[3]; // Convert the price to integer
        $harddisk = (int)$columns[4]; // Convert the price to integer
        $videocontroller = $conn->real_escape_string($columns[5]);
        $price = (int)$columns[6]; // Convert the price to integer
        $processorid = (int)$columns[7]; // Convert the price to integer
        $opsystemid = (int)$columns[8]; // Convert the price to integer
        $pieces = (int)$columns[9]; // Convert the price to integer

        $number_of_row = $number_of_row + 1;
        // Create the SQL query to insert data
        $sql = "INSERT INTO notebook (manufacturer, type, display, memory, harddisk, videocontroller, price, processorid, opsystemid, pieces, id) VALUES ('$manufacturer', '$type', $display, $memory, $harddisk, '$videocontroller', $price, $processorid, $opsystemid, $pieces, $id)";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "New row created: $id - $manufacturer - $type - $price<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

/////////////////////////////

echo "////////////////////////////////<br>///////OPSYSTEM///////<br>////////////////////////////////<br>";

// Read the text file
$file = __DIR__ . '/images/opsystem.txt'; // Relative path to the text file


// Check if the file exists
if (!file_exists($file)) {
    die("The file was not found.");
}

// Read the content of the file as a string
$data = file_get_contents($file);

// Split the content of the file into lines (one for each record)
$rows = explode(PHP_EOL, $data);

// Remove the first row (headers)
array_shift($rows);

// Insert each row into the database
foreach ($rows as $row) {
    // Split the row by tabs
    $columns = explode("\t", $row);

    // Ensure there are no empty rows
    if (count($columns) > 1) {
        $name = $conn->real_escape_string($columns[1]); // Escape quotes for security
        $id = (int)$columns[0]; // Convert the ID to integer
        // Create the SQL query to insert data
        $sql = "INSERT INTO opsystem (id, name) VALUES ($id, '$name')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "New row created: $id - $name<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

/////////////////////////////

echo "////////////////////////////////<br>///////PROCESSOR///////<br>////////////////////////////////<br>";

// Read the text file
$file = __DIR__ . '/images/processor.txt'; // Relative path to the text file


// Check if the file exists
if (!file_exists($file)) {
    die("The file was not found.");
}

// Read the content of the file as a string
$data = file_get_contents($file);

// Split the content of the file into lines (one for each record)
$rows = explode(PHP_EOL, $data);

// Remove the first row (headers)
array_shift($rows);

// Insert each row into the database
foreach ($rows as $row) {
    // Split the row by tabs
    $columns = explode("\t", $row);

    if (count($columns) > 1) {
        $manu = $conn->real_escape_string($columns[1]); // manufacturer
        $type = $conn->real_escape_string($columns[2]); // type
        $id = (int)$columns[0]; // Convert the ID to integer
        
        // Create the SQL query to insert data
        $sql = "INSERT INTO processor (id, manufacturer, type) VALUES ($id, '$manu', '$type')";
    
        // Execute the query
        if ($conn->query($sql) === TRUE) {
            echo "New row created: $id - $manu - $type<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    
}

// Close the connection
$conn->close();
?>
