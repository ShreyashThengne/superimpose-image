<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Form</title>
</head>
<body>
    <h1>Enter Your Details</h1>
    <form action="/Superimpose_task/get_dimensions.php" method="post">
        <label for="Wall_Height">Wall Height (CM):</label>
        <input type="number" id="Wall_Height" name="Wall_Height" required>
        <br><br>
        
        <label for="Wall_Width">Wall Width (CM):</label>
        <input type="number" id="Wall_Width" name="Wall_Width" required>
        <br><br>
        
        <button type="Generate">Submit</button>
    </form>
</body>
</html>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wall_width = $_POST['Wall_Width'];
    $wall_height = $_POST['Wall_Height'];
    
    $ch = curl_init();
    
    $data = http_build_query(array(
        'Wall_Width' => $wall_width,
        'Wall_Height' => $wall_height
    ));
    
    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:5000/get_dimensions");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    echo $response;  # this was done purposely as the output is only availabl with the flask's webpage
}
?>
