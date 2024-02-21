<?php
// Include sizes.php file
include 'sizes.php';

// Function to resize image while maintaining aspect ratio
function resizeImage($image, $maxWidth, $maxHeight) {
    if ($image === null) {
        return null;
    }
    
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);
    
    $aspectRatio = $originalWidth / $originalHeight;
    
    if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
        if ($maxWidth / $maxHeight > $aspectRatio) {
            $maxWidth = $maxHeight * $aspectRatio;
        } else {
            $maxHeight = $maxWidth / $aspectRatio;
        }
    }

    $resizedImage = imagecreatetruecolor((int)$maxWidth, (int)$maxHeight);
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, (int)$maxWidth, (int)$maxHeight, $originalWidth, $originalHeight);
    return $resizedImage;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $fileCount = count($_FILES["image"]["name"]);
    
    // Display the resized images and download links within the result div
    echo '<div class="result">';
    for ($i = 0; $i < $fileCount; $i++) {
        $file = $_FILES["image"]["tmp_name"][$i];
        
        // Check if file is an image
        if (!isset($_FILES["image"]["tmp_name"][$i]) || !file_exists($_FILES["image"]["tmp_name"][$i])) {
            die("Failed to upload image.");
        }

        // Check if file is an image
        $allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
        $detectedType = exif_imagetype($file);
        if (!in_array($detectedType, $allowedTypes)) {
            die("Invalid file type. Only PNG, JPEG, and GIF files are allowed.");
        }

        // Resize the image based on selected sizes
        $originalImage = imagecreatefromstring(file_get_contents($file));
        
        $selectedSizes = $_POST["sizes"]; // Get selected sizes from the form
        
        foreach ($selectedSizes as $size) {
            if (isset($sizes[$size])) { 
                // Check if size exists in the $sizes array
                list($width, $height) = explode('x', substr($sizes[$size], strrpos($sizes[$size], '(') + 1, -1)); // Extract width and height
                $resizedImage = resizeImage($originalImage, $width, $height); // Resize the image
                
                // Save and display resized image
                if ($resizedImage !== null) {
                    $timestamp = time(); // Get current timestamp
                    $filename = $size . '-' . $width . 'x' . $height . '-' . $timestamp . '-' . $i . '.jpg';
                    imagejpeg($resizedImage, $filename);
                    echo "<h3>" . htmlspecialchars($sizes[$size]) . "</h3>";
                    echo "<p><img src=\"$filename\"></p>";
                    echo "<a href=\"$filename\" download>Download " . htmlspecialchars($sizes[$size]) . "</a><br>";
                   
                } else {
                    echo "<p>Error resizing image for size '" . htmlspecialchars($sizes[$size]) . "'.</p>";
                }
            } else {
                echo "<p>Size '$size' is not defined in the sizes array.</p>";
            }
        }
    }
    echo '</div>';
    echo '<a href="index.php">Go Home</a><br>';
} else {
    // If no file was uploaded, or other error occurred, redirect back to the upload page
    header("Location: index.php");
    exit();
}

// Suggested cleanup script (cleanup.php)
$cleanupPeriod = 300; // Cleanup images older than 24 hours
$files = glob('*-*-*-*.jpg'); // Adjust pattern based on your filenames

foreach ($files as $file) {
    $fileTimestamp = intval(explode('-', $file)[2]);
print_r($fileTimestamp);
    if (time() - $fileTimestamp > $cleanupPeriod) {
        unlink($file);
    }
}