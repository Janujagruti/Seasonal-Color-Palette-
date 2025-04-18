<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seasonal Color Analysis</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to the external CSS file -->
</head>
<body>
<?php if (isset($_GET['colorPalette'])): ?>
    <div class="results">
        <h3>Your Color Palette:</h3>
        <p><?php echo htmlspecialchars($_GET['colorPalette']); ?></p>

        <h3>Best Colors for You to Wear:</h3>
        <p><?php echo htmlspecialchars($_GET['bestColor']); ?></p>

        <h3>Colors You Should Avoid:</h3>
        <p><?php echo htmlspecialchars($_GET['worstColor']); ?></p>

        <!-- Display the user’s email from the URL query string -->
        <h3>Your Email:</h3>
        <p><?php echo htmlspecialchars($_GET['email']); ?></p>

        <!-- Add a Download button -->
        <a href="path_to_palette_image.png" download="color_palette.png">
            <button type="button">Download Palette</button>
        </a>
    </div>
<?php endif; ?>


</body>
</html>
