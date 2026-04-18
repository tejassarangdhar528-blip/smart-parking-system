<?php
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php?role=admin");
    exit();
}

// Handle adding spot
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['spot_number'])) {
    $spot_number = $_POST['spot_number'];
    $stmt = $conn->prepare("INSERT INTO parking_spots (spot_number) VALUES (?)");
    $stmt->bind_param("s", $spot_number);
    if ($stmt->execute()) {
        $message = "Spot added successfully!";
    } else {
        $message = "Failed to add spot.";
    }
}

// Get all spots
$spots = $conn->query("SELECT * FROM parking_spots");

// Get all bookings
$bookings = $conn->query("SELECT b.id, u.username, p.spot_number, b.start_time, b.status FROM bookings b JOIN users u ON b.user_id = u.id JOIN parking_spots p ON b.spot_id = p.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Parking</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Add Parking Spot</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="spot_number">Spot Number:</label>
            <input type="text" id="spot_number" name="spot_number" required>
            <button type="submit">Add Spot</button>
        </form>
        
        <h2>All Parking Spots</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Spot Number</th>
                <th>Status</th>
            </tr>
            <?php while ($spot = $spots->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $spot['id']; ?></td>
                    <td><?php echo $spot['spot_number']; ?></td>
                    <td><?php echo $spot['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <h2>All Bookings</h2>
        <table>
            <tr>
                <th>User</th>
                <th>Spot</th>
                <th>Start Time</th>
                <th>Status</th>
            </tr>
            <?php while ($booking = $bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $booking['username']; ?></td>
                    <td><?php echo $booking['spot_number']; ?></td>
                    <td><?php echo $booking['start_time']; ?></td>
                    <td><?php echo $booking['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2023 Smart Parking System</p>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>