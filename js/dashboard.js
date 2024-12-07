function openModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.style.display = 'flex'; // Change to flex for proper centering
}

function closeModal() {
    const modal = document.getElementById('addPropertyModal');
    modal.style.display = 'none';
}

function addProperty() {
    const form = document.getElementById('addPropertyForm');
    const formData = new FormData(form);

    fetch('save_property.php', {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                alert(data.message);
                location.reload(); // Reload to fetch the updated property list
            } else {
                alert(data.message);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}


// Open Edit Modal
function openEditModal(propertyId) {
    fetch(`get_property.php?property_id=${propertyId}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                // Populate the form with the property data
                document.getElementById("edit_property_id").value = data.property.id;
                document.getElementById("edit_location").value = data.property.location;
                document.getElementById("edit_floor_plan").value = data.property.floor_plan;
                document.getElementById("edit_num_bedrooms").value = data.property.num_bedrooms;
                document.getElementById("edit_base_value").value = data.property.base_value;
                document.getElementById("editPropertyModal").style.display = "block";
            } else {
                alert(data.message);
            }
        })
        .catch((error) => console.error("Error:", error));
}

// Close Edit Modal
function closeEditModal() {
    document.getElementById("editPropertyModal").style.display = "none";
}

// Update Property
function updateProperty() {
    const formData = new FormData(document.getElementById("editPropertyForm"));

    fetch("update_property.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch((error) => console.error("Error:", error));
}

// Open Delete Modal
function openDeleteModal(propertyId) {
    document.getElementById("delete_property_id").value = propertyId;
    document.getElementById("deleteConfirmationModal").style.display = "block";
}

// Close Delete Modal
function closeDeleteModal() {
    document.getElementById("deleteConfirmationModal").style.display = "none";
}

// Confirm Delete
function confirmDelete() {
    const propertyId = document.getElementById("delete_property_id").value;

    fetch(`delete_property.php?property_id=${propertyId}`, {
        method: "GET",
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "success") {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch((error) => console.error("Error:", error));
}
