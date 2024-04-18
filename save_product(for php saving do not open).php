<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON data sent from JavaScript
    $productData = json_decode(file_get_contents("php://input"), true);

    // Log the received product data for debugging
    error_log("Received product data: " . print_r($productData, true));

    // Validate the received data
    if (empty($productData['name']) || empty($productData['quantity']) || empty($productData['price']) || empty($productData['supplier']) || empty($productData['expirationDays'])) {
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Incomplete product data."));
        exit;
    }

    // Connect to your MySQL database
    $conn = new mysqli("localhost", "admin", "admin123", "products");

    // Check the connection
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Prepare the SQL statement to insert the product data
        $stmt = $conn->prepare("INSERT INTO products (name, quantity, price, supplier, date_added, expiration_date) VALUES (?, ?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))");

        // Bind the parameters and execute the statement
        $stmt->bind_param("sidsi", $productData['name'], $productData['quantity'], $productData['price'], $productData['supplier'], $productData['expirationDays']);
        $stmt->execute();
        echo json_encode(array("message" => "Product saved successfully."));
        $stmt->close();
        $conn->close();
    }
}
?>
