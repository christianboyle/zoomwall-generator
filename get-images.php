<?php
header('Content-Type: application/json');

$imagesDir = './images/';

// Check if images directory exists
if (!is_dir($imagesDir)) {
    echo json_encode([
        'error' => 'Images directory not found. Please create an "images" folder and add your images.'
    ]);
    exit;
}

$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Check if any images exist
if (empty($images)) {
    echo json_encode([
        'error' => 'No images found in the images directory. Please add some images.'
    ]);
    exit;
}

// Sort images numerically by filename
usort($images, function($a, $b) {
    $numA = intval(preg_replace('/[^0-9]/', '', basename($a)));
    $numB = intval(preg_replace('/[^0-9]/', '', basename($b)));
    return $numA - $numB;
});

$imageFiles = array_map(function($path) {
    $filename = basename($path);
    // Get image dimensions if needed
    $size = getimagesize($path);
    return [
        'filename' => $filename,
        'width' => $size[0],
        'height' => $size[1]
    ];
}, $images);

echo json_encode(['images' => $imageFiles]);
?> 