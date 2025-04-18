<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Your database password
$dbname = "color_theory_data"; // Updated database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data from POST request
$userName = isset($_POST['name']) ? $_POST['name'] : null;
$skinColor = isset($_POST['skin']) ? $_POST['skin'] : null;
$eyeColor = isset($_POST['eye']) ? $_POST['eye'] : null;
$hairColor = isset($_POST['hair']) ? $_POST['hair'] : null;
$email = isset($_POST['email']) ? $_POST['email'] : null; // Capture the email from form

// Validate if all data is provided
if (!$userName || !$skinColor || !$eyeColor || !$hairColor || !$email) {
    echo "Please fill in all fields.";
    exit();
}

// Insert user data into the user_data table
$stmt = $conn->prepare("INSERT INTO user_data (user_name, skin_color, eye_color, hair_color, email) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $userName, $skinColor, $eyeColor, $hairColor, $email);
$stmt->execute();
$user_id = $stmt->insert_id; // Get the ID of the inserted user
$stmt->close();

// Compare user data with existing_data table
$query = "SELECT * FROM existing_data WHERE skin_color = ? AND eye_color = ? AND hair_color = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $skinColor, $eyeColor, $hairColor);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Found matching data, retrieve it
    $row = $result->fetch_assoc();
    
    // Insert the result into the result_data table
    $stmt = $conn->prepare("INSERT INTO result_data (user_id, existing_data_id, color_palette, best_color, worst_color) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $user_id, $row['id'], $row['color_palette'], $row['best_color'], $row['worst_color']);
    $stmt->execute();
    $stmt->close();

    // Prepare response to pass back as URL query parameters
    $colorPalette = urlencode($row['color_palette']);
    $bestColor = urlencode($row['best_color']);
    $worstColor = urlencode($row['worst_color']);

    // Redirect back to the form with the results, including email
    header("Location: index.php?colorPalette=$colorPalette&bestColor=$bestColor&worstColor=$worstColor&email=" . urlencode($email));
} else {
    // No match found, return unknown results
    $colorPalette = "Unknown";
    $bestColor = "Unknown";
    $worstColor = "Unknown";

    // Redirect back to the form with the unknown results, including email
    header("Location: index.php?colorPalette=$colorPalette&bestColor=$bestColor&worstColor=$worstColor&email=" . urlencode($email));
}

$conn->close();
?>
