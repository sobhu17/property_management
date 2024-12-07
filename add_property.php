<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Property</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h2>Add Property</h2>
    <form action="save_property.php" method="POST" enctype="multipart/form-data">
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

        <input type="submit" value="Save Property">
    </form>
</div>
</body>
</html>
