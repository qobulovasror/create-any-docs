<?php

// Receive data sent from JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Process the received data
if ($data) {
    // For demonstration, just echoing the received data
    echo "Received data on server: ";
    print_r($data);

    $fp = fopen('lidn.txt', 'w');
    fwrite($fp, $data);
    fwrite($fp, 'mice');
    fclose($fp);
} else {
    $fp = fopen('lidn1.txt', 'w');
    fwrite($fp, $data);
    fwrite($fp, 'mice');
    fclose($fp);
    echo "No data received.";
}