<?php
// callback.php
// Read the JSON payload from MTN MoMo
$data = json_decode(file_get_contents("php://input"), true);

// Check if the data was successfully received
if ($data) {
    // Extract relevant payment details
    $status = $data['status'];
    $amount = $data['amount'];
    $transactionId = $data['financialTransactionId'];
    $externalId = $data['externalId'];
    
    // Log or update the payment information in your database
    if ($status == "SUCCESSFUL") {
        // Code to mark the payment as complete
        echo "Payment was successful.";
    } else {
        // Handle other statuses like FAILED or PENDING
        echo "Payment status: $status";
    }

    // Send a response back to MTN MoMo
    http_response_code(200);
    echo json_encode(['status' => 'received']);
} else {
    // Handle cases where the data was not received
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
}
?>
