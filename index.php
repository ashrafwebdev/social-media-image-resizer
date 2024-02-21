<?php 
include "sizes.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Images Resizer</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Social Media Images Resizer</h1>
        <form id="uploadForm" method="post" action="upload.php" enctype="multipart/form-data">
        <div class="file-input">
        <label for="image">Select images to upload:</label><br>
        <input type="file" name="image[]" id="image" multiple required>
    </div>
            <div class="checkbox-group">
            
                <label>Select sizes for resizing:</label><br>
                <label><input type="checkbox" id="select-all"> Select All</label><br>
                <?php foreach ($sizes as $key => $value): ?>
                    <input type="checkbox" class="size-checkbox" name="sizes[]" value="<?php echo $key; ?>"> <?php echo $value; ?><br>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="submit-button">Upload Image</button>
        </form>

       
    </div>

    <script src="script.js"></script>
   
</body>
</html>

