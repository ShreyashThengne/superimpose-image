<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload Form</title>
</head>
<body>
    <h1>Upload an Image</h1>
    <form action="/Superimpose_task/upload_image.php" method="post" enctype="multipart/form-data">
        <label for="imageUpload">Select an image to upload:</label>
        <input type="file" id="imageUpload" name="imageUpload" accept="image/*">
        <br><br>
        <button type="submit">Next</button>
    </form>
</body>
</html>


<?php

// first it will take the input in the form
// then page is again redirected to the this page again but now it will have the php will be executed
// php will check whether image is uploaded and then create the curl object and set to the flask api
// api will send the template (webpage) as a response. I did not use it right now though
// then we will be redirected to the next page (wall_size_input.php)


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == 0) {   # this makes sure that this php is executed only when image is uploaded
        $file_tmp = $_FILES['imageUpload']['tmp_name'];
        $file_name = $_FILES['imageUpload']['name'];
        
        $ch = curl_init();
        $cfile = new CURLFile($file_tmp, mime_content_type($file_tmp), $file_name);
        
        $data = array('image' => $cfile);
        
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:5000/upload");  # sets the ch to this api link
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        header("Location: wall_size_input.php");  # this will redirect the page to wall_size_input.php
        exit();
    } else {
        echo "No file uploaded or there was an upload error.";
    }
}
?>
