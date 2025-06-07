<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $lat = $_POST['lat'] ?? '';
    $lon = $_POST['lon'] ?? '';

    if (!$name && !$note) {
        exit("No content to save.");
    }

    $entry = "Name: " . htmlspecialchars($name) . "\n" .
             "Note: " . htmlspecialchars($note) . "\n" .
             "Latitude: $lat, Longitude: $lon\n" .
             "Saved at: " . date('Y-m-d H:i:s') . "\n" .
             "---------------------------\n";

    $fp = fopen("saved_locations.txt", "a");
    if (flock($fp, LOCK_EX)) {
        fwrite($fp, $entry);
        flock($fp, LOCK_UN);
        fclose($fp);
        echo "Location saved successfully.";
    } else {
        echo "Could not write to file.";
    }
} else {
    echo "Invalid request.";
}
