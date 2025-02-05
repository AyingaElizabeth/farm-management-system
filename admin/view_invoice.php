<?php
header('Content-Disposition: inline; filename="' . $file . '"');

if (isset($_GET['file'])) {
    $file = basename($_GET['file']);
    $invoice_directory = '../invoices/';
    $file_path = $invoice_directory . $file;

    if (file_exists($file_path)) {
        // Set headers to display the PDF in the browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $file . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        echo 'File not found.';
    }
} else {
    echo 'No file specified.';
}
?>
