<?php
// Sanitize and validate the file path
$filePath = isset($_GET['file']) ? $_GET['file'] : null;
if (!$filePath || !is_file($filePath)) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid or missing file parameter.';
    exit;
}

try {
    // Initialize Imagick
    $im = new Imagick();
    $im->readImage($filePath);
    $im->stripImage(); // Remove unnecessary metadata

    // Set image format and compression
    $im->setImageFormat('webp');
    $im->setImageCompressionQuality(75); // Use a constant for quality if desired

    // Output the image as a WebP format
    header('Content-Type: image/webp');
    echo $im->getImagesBlob();
    $im->writeImage($filePath.".webp");
} catch (Exception $e) {
    // Handle errors gracefully
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Error processing image: ' . $e->getMessage();
} finally {
    // Clean up
    if (isset($im)) {
        $im->clear();
    }
}
?>
