<?php
// Initialize the SOAP client
$client = new SoapClient('http://localhost/Web-2-Seminar-Homework/admin_service.wsdl');

// Function to call SOAP methods
function callSoapMethod($method, $params) {
    global $client;
    try {
        $response = $client->__soapCall($method, $params);
        return $response;
    } catch (SoapFault $e) {
        echo "SOAP Fault: " . $e->getMessage();
    }
}

// Test Adding a Reference Code
$code = 'NEW_REF_CODE_123';
$response = callSoapMethod('addReferenceCode', ['code' => $code]);
echo "Add Reference Code Response: " . ($response ? 'Success' : 'Failure') . "\n";
 
?>
