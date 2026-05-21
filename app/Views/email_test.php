<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Configuration Test - CSPC Lost and Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .test-section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 5px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 15px;
            padding: 15px;
            border-radius: 5px;
            white-space: pre-wrap;
        }
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        .loading {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Configuration Test</h1>
        <p>Use this page to test the email functionality of the CSPC Lost and Found system.</p>
        
        <div class="test-section">
            <h3>1. Basic SMTP Configuration Test</h3>
            <p>Tests the basic email sending functionality using SMTP settings.</p>
            <button onclick="testBasicEmail()">Test Basic Email</button>
            <div id="basicResult" class="result" style="display: none;"></div>
        </div>
        
        <div class="test-section">
            <h3>2. EmailNotificationService Test</h3>
            <p>Tests the EmailNotificationService library used for claim notifications.</p>
            <button onclick="testNotificationService()">Test Notification Service</button>
            <div id="serviceResult" class="result" style="display: none;"></div>
        </div>
    </div>

    <script>
        function testBasicEmail() {
            const resultDiv = document.getElementById('basicResult');
            resultDiv.style.display = 'block';
            resultDiv.className = 'result loading';
            resultDiv.textContent = 'Testing basic email configuration...';
            
            fetch('/emailtest/testEmail')
                .then(response => response.json())
                .then(data => {
                    resultDiv.className = data.status === 'success' ? 'result success' : 'result error';
                    resultDiv.textContent = JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    resultDiv.className = 'result error';
                    resultDiv.textContent = 'Error: ' + error.message;
                });
        }
        
        function testNotificationService() {
            const resultDiv = document.getElementById('serviceResult');
            resultDiv.style.display = 'block';
            resultDiv.className = 'result loading';
            resultDiv.textContent = 'Testing EmailNotificationService...';
            
            fetch('/emailtest/testNotificationService')
                .then(response => response.json())
                .then(data => {
                    resultDiv.className = data.status === 'success' ? 'result success' : 'result error';
                    resultDiv.textContent = JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    resultDiv.className = 'result error';
                    resultDiv.textContent = 'Error: ' + error.message;
                });
        }
    </script>
</body>
</html>