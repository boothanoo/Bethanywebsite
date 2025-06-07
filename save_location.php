<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $note = $_POST['note'] ?? '';
    $lat = $_POST['lat'] ?? '';
    $lon = $_POST['lon'] ?? '';
    $uploadDir = 'uploads/';
    $photoPath = '';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $tmpName = $_FILES['photo']['tmp_name'];
        $originalName = basename($_FILES['photo']['name']);
        $safeName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "_", $originalName);
        $destination = $uploadDir . $safeName;

        if (move_uploaded_file($tmpName, $destination)) {
            $photoPath = $destination;
        }
    }

    $entry = "Name: " . strip_tags($name) . "\n" .
             "Note: " . strip_tags($note) . "\n" .
             "Latitude: $lat, Longitude: $lon\n" .
             ($photoPath ? "Photo: $photoPath\n" : "Photo: None\n") .
             "Saved at: " . date('Y-m-d H:i:s') . "\n" .
             "---------------------------\n";

    file_put_contents("saved_locations.txt", $entry, FILE_APPEND);

    echo "Location info saved.";
} else {
    echo "Invalid request.";
}
