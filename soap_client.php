<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title>Notebooks</title>
  </head>

  <?php 
  try {
    // Create SOAP client
    $client = new SoapClient(null, array(
      'location' => 'http://localhost/FirstStep/Web-2-Seminar-Homework/soap_server.php', // URL to the server
      'uri' => 'urn://localhost/FirstStep/Web-2-Seminar-Homework/soap_server.php', // URI
      'trace' => 1,  // Enable trace to see the request and response
      'exceptions' => true  // Enable exceptions for error handling
    ));

    // Call the getNotebooks function
    $notebooks = $client->__soapCall('getNotebooks', array());

    // Debugging: Show SOAP request and response
    echo "<pre>";
    echo "SOAP Request:\n";
    echo htmlentities($client->__getLastRequest());  // Show the SOAP request
    echo "\nSOAP Response:\n";
    echo htmlentities($client->__getLastResponse());  // Show the SOAP response
    echo "</pre>";

    // Handle POST request for notebook selection
    if (isset($_POST['notebook']) && trim($_POST['notebook']) != "") {
      $details = $client->__soapCall('getNotebookDetails', array($_POST['notebook']));
    }

  } catch (SoapFault $fault) {
    // Handle any SOAP errors
    echo "Error: " . $fault->getMessage();
  }
  ?>

  <body>
    <h1>Notebooks</h1>
    <form name="notebookselect" method="POST">
      <select name="notebook" onchange="javascript:notebookselect.submit();">
        <option value="">Select a Notebook...</option>
        <?php
          // Loop through the notebooks and display them in the dropdown
          if ($notebooks) {
            foreach ($notebooks as $notebook) {
              echo '<option value="'.$notebook['id'].'">'.$notebook['notebook_name'].'</option>';
            }
          }
        ?>
      </select>
    </form>
    
    <?php
      // If notebook details are available, display them
      if (isset($details)) {
        echo "<h2>Notebook Details</h2>";
        echo "Name: " . $details['notebook_name'] . "<br>";
        echo "Processor: " . $details['processor_name'] . "<br>";
        echo "Operating System: " . $details['opsystem_name'] . "<br>";
      }
    ?>
  </body>
</html>
