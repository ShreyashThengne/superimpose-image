<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Form</title>
</head>
<body>
    <h1>Enter Your Details</h1>
    <form action="/Superimpose_task/wall_size_input.php" method="post">
        <label for="left">left:</label>
        <input type="number" id="left" name="left" required>
        <br><br>
        
        <label for="right">right:</label>
        <input type="number" id="right" name="right" required>
        <br><br>
        
        <label for="top">top:</label>
        <input type="number" id="top" name="top" required>
        <br><br>
        
        <label for="bottom">bottom:</label>
        <input type="number" id="bottom" name="bottom" required>
        <br><br>
        
        <button type="Next">Submit</button>
    </form>
</body>
</html>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // initializing variables
    $left = isset($_POST['left']) ? $_POST['left'] : null;
    $right = isset($_POST['right']) ? $_POST['right'] : null;
    $top = isset($_POST['top']) ? $_POST['top'] : null;
    $bottom = isset($_POST['bottom']) ? $_POST['bottom'] : null;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $left = $_POST['left'];
    $right = $_POST['right'];
    $top = $_POST['top'];
    $bottom = $_POST['bottom'];
    
    $ch = curl_init();
    
    $data = http_build_query(array(
        'left' => $left,
        'right' => $right,
        'top' => $top,
        'bottom' => $bottom
    ));
    
    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:5000/wall_size");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // echo $response;
    header("Location: get_dimensions.php");
}
?>
