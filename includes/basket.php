<!-- index.html -->
<!DOCTYPE html>
<html>
<head>
    <title>RESTful API Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>RESTful API Client - Laptop Management</h2>
        
        <!-- API Endpoint Input -->
        <div class="mb-3">
            <label>API Endpoint:</label>
            <input type="text" id="apiEndpoint" class="form-control" value="http://your-api-url/api/laptops" />
        </div>

        <!-- HTTP Method Selection -->
        <div class="mb-3">
            <label>HTTP Method:</label>
            <select id="httpMethod" class="form-control">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </select>
        </div>

        <!-- Request Body -->
        <div class="mb-3">
            <label>Request Body (for POST/PUT):</label>
            <textarea id="requestBody" class="form-control" rows="5">
{
    "manufacturer": "HP",
    "type": "Pavilion",
    "price": 999.99,
    "display": 15.6,
    "memory": 16,
    "harddisk": 512,
    "videocontroller": "NVIDIA GeForce",
    "pieces": 10
}
            </textarea>
        </div>

        <!-- ID Input for PUT/DELETE -->
        <div class="mb-3">
            <label>ID (for PUT/DELETE):</label>
            <input type="text" id="itemId" class="form-control" />
        </div>

        <!-- Submit Button -->
        <button onclick="sendRequest()" class="btn btn-primary">Send Request</button>

        <!-- Response Area -->
        <div class="mt-4">
            <h4>Response:</h4>
            <pre id="response" class="border p-3 bg-light"></pre>
        </div>
    </div>

    <script>
        async function sendRequest() {
            const endpoint = document.getElementById('apiEndpoint').value;
            const method = document.getElementById('httpMethod').value;
            const body = document.getElementById('requestBody').value;
            const itemId = document.getElementById('itemId').value;

            let url = endpoint;
            if ((method === 'PUT' || method === 'DELETE') && itemId) {
                url = `${endpoint}/${itemId}`;
            }

            try {
                const headers = {
                    'Content-Type': 'application/json',
                    // Add any authentication headers if needed
                    // 'Authorization': 'Bearer your-token'
                };

                const options = {
                    method: method,
                    headers: headers,
                };

                if (method === 'POST' || method === 'PUT') {
                    options.body = body;
                }

                const response = await fetch(url, options);
                const data = await response.json();
                
                document.getElementById('response').innerHTML = 
                    JSON.stringify(data, null, 2);
            } catch (error) {
                document.getElementById('response').innerHTML = 
                    `Error: ${error.message}`;
            }
        }
    </script>
</body>
</html>