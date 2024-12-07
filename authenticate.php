<?php
session_start();

// Database Connection
$servername = "localhost"; 
$username = "skaushik5";  
$password = "skaushik5";      
$dbname = "skaushik5";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = trim($_POST['email']);
$user_password = trim($_POST['password']);

// Check user credentials
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify password
    if (password_verify($user_password, $row['password'])) {
        // Generate session token
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['session_token'] = bin2hex(random_bytes(32));
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_type'] = $row['user_type'];

        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Incorrect password!";
        header("Location: signin.php");
        exit;
    }
} else {
    $_SESSION['error'] = "User not found!";
    header("Location: signin.php");
    exit;
}

$stmt->close();
$conn->close();
?>
