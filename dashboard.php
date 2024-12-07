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

// Fetch properties created by the seller
$seller_id = $_SESSION['user_id'];
$sql = "SELECT p.*, a.features 
        FROM property p 
        LEFT JOIN amenity a ON p.id = a.property_id 
        JOIN user_property up ON p.id = up.property_id 
        WHERE up.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<div class="navbar">
    <div class="navbar-content">
        <h2>Seller Dashboard</h2>
        <div class="nav-actions">
            <a href="add_property.php" class="add-property-btn">+ Add Property</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</div>

<div class="container">
    <h2>Your Properties</h2>
    <?php if ($result->num_rows > 0): ?>
        <div class="property-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="property-card">
                    <img src="images/uploads/<?= htmlspecialchars($row['image']) ?>" alt="Property Image">
                    <div class="property-details">
                        <h3><?= htmlspecialchars($row['location']) ?></h3>
                        <p><strong>Floor Plan:</strong> <?= htmlspecialchars($row['floor_plan']) ?></p>
                        <p><strong>Bedrooms:</strong> <?= htmlspecialchars($row['num_bedrooms']) ?></p>
                        <p><strong>Base Value:</strong> $<?= htmlspecialchars($row['base_value']) ?></p>
                        <p><strong>Amenities:</strong> <?= htmlspecialchars($row['features'] ?: "Not added yet") ?></p>
                        <a href="edit_amenities.php?property_id=<?= $row['id'] ?>" class="edit-btn">Edit/Add Amenities</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="no-properties">
            <p>No properties found. Click "+ Add Property" to create one.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
