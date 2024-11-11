<?php
try {
    // Database connection
    $dbh = new PDO('mysql:host=localhost;dbname=laptop', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $dbh->exec('SET NAMES utf8 COLLATE utf8_general_ci');
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Database connection failed: ' . $e->getMessage()));
}

// SOAP server function
function getNotebooks() {
    global $dbh;
    try {
        $sql = "SELECT * FROM notebook";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $notebooks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Ensure the result is in proper XML format
        $response = new SimpleXMLElement('<response/>');
        foreach ($notebooks as $notebook) {
            $notebookXML = $response->addChild('notebook');
            $notebookXML->addChild('id', $notebook['id']);
            $notebookXML->addChild('name', $notebook['notebook_name']);
        }
        // Return the generated XML
        return $response->asXML();
    } catch (PDOException $e) {
        // Handle any exceptions
        return new SoapFault('Server', 'Error: ' . $e->getMessage());
    }
}

// Create the SOAP server
$server = new SoapServer(null, array(
    'uri' => 'urn://localhost/soap_server.php', // URI
    'location' => 'http://localhost/FirstStep/Web-2-Seminar-Homework/soap_server.php' // Your SOAP server URL
));

// Register the function to be exposed via SOAP
$server->addFunction('getNotebooks');

// Handle the SOAP request
$server->handle();
?>
