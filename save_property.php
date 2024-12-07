<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seller') {
    header("Location: signin.php");
    exit;
}

$servername = "localhost"; 
$username = "skaushik5";  
$password = "skaushik5";      
$dbname = "skaushik5";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$location = $_POST['location'];
$floor_plan = $_POST['floor_plan'];
$num_bedrooms = $_POST['num_bedrooms'];
$base_value = $_POST['base_value'];
$seller_id = $_SESSION['user_id'];

// Handle image upload
$image_name = $_FILES['image']['name'];
$image_tmp = $_FILES['image']['tmp_name'];
$upload_dir = "images/uploads/";
$image_path = $upload_dir . basename($image_name);

if (move_uploaded_file($image_tmp, $image_path)) {
    $insert_property = "INSERT INTO property (location, floor_plan, num_bedrooms, base_value, image) 
                        VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_property);
    $stmt->bind_param("ssids", $location, $floor_plan, $num_bedrooms, $base_value, $image_name);

    if ($stmt->execute()) {
        $property_id = $stmt->insert_id;

        // Map user to property
        $map_user_property = "INSERT INTO user_property (user_id, property_id) VALUES (?, ?)";
        $map_stmt = $conn->prepare($map_user_property);
        $map_stmt->bind_param("ii", $seller_id, $property_id);
        $map_stmt->execute();

        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error saving property: " . $conn->error;
    }
} else {
    echo "Error uploading image.";
}

$conn->close();
?>
