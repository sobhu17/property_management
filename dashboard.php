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
            <button class="add-property-btn" onclick="openModal()">+ Add Property</button>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</div>

<div class="container">
    <h2>Your Properties</h2>
    <div id="property-table-container">
        <?php if ($result->num_rows > 0): ?>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Location</th>
                        <th>Floor Plan</th>
                        <th>Bedrooms</th>
                        <th>Base Value</th>
                        <th>Amenities</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($row['image']) ?>" alt="Property Image" class="property-img">
                            </td>
                            <td><?= htmlspecialchars($row['location']) ?></td>
                            <td><?= htmlspecialchars($row['floor_plan']) ?></td>
                            <td><?= htmlspecialchars($row['num_bedrooms']) ?></td>
                            <td>$<?= htmlspecialchars($row['base_value']) ?></td>
                            <td><?= htmlspecialchars($row['features'] ?: "Not added yet") ?></td>
                            <td class="actions">
                                <div class="action-icons">
                                    <a href="javascript:void(0)" onclick="openEditModal(<?= $row['id'] ?>)">
                                        <img src="icons/edit-icon.png" alt="Edit">
                                    </a>
                                    <a href="javascript:void(0)" onclick="openDeleteModal(<?= $row['id'] ?>)">
                                        <img src="icons/delete-icon.png" alt="Delete">
                                    </a>
                                    <a href="edit_amenities.php?property_id=<?= $row['id'] ?>" class="action-icon">
                                        <img src="icons/add-icon.png" alt="Add Amenities" title="Add Amenities">
                                    </a>
                                </div>
                            </td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-properties">
                <p>No properties found. Click "+ Add Property" to create one.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Property Modal -->
<div id="addPropertyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Add Property</h2>
        <form id="addPropertyForm" enctype="multipart/form-data">
            <label>Location</label>
            <input type="text" name="location" required>

            <label>Floor Plan</label>
            <input type="text" name="floor_plan" required>

            <label>Number of Bedrooms</label>
            <input type="number" name="num_bedrooms" required min="1">

            <label>Base Value ($)</label>
            <input type="number" name="base_value" required min="0" step="0.01">

            <label>Property Image</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="button" onclick="addProperty()">Add Property</button>
        </form>
    </div>
</div>

<!-- Edit Property Modal -->
<div id="editPropertyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h2>Edit Property</h2>
        <form id="editPropertyForm" enctype="multipart/form-data">
            <input type="hidden" name="property_id" id="edit_property_id">
            
            <label>Location</label>
            <input type="text" name="location" id="edit_location" required>

            <label>Floor Plan</label>
            <input type="text" name="floor_plan" id="edit_floor_plan" required>

            <label>Number of Bedrooms</label>
            <input type="number" name="num_bedrooms" id="edit_num_bedrooms" required min="1">

            <label>Base Value ($)</label>
            <input type="number" name="base_value" id="edit_base_value" required min="0" step="0.01">

            <label>Property Image</label>
            <input type="file" name="image" id="edit_image" accept="image/*">
            
            <button type="button" onclick="updateProperty()">Update Property</button>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
        <h2>Confirm Delete</h2>
        <p>Are you sure you want to delete this property?</p>
        <button onclick="confirmDelete()" class="delete-btn">Delete</button>
        <button onclick="closeDeleteModal()" class="cancel-btn">Cancel</button>
        <input type="hidden" id="delete_property_id">
    </div>
</div>


<script src="js/dashboard.js"></script>
</body>
</html>
