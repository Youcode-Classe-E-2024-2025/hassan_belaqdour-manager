<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    $conn = new mysqli("localhost", "root", "", "securite");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "UPDATE users SET status_id = (SELECT id FROM status WHERE name = 'admin') WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        echo "User successfully promoted to Admin!";
        header("Refresh: 0; url=dashboard.php");
    } else {
        echo "Error:probleme de update vers admin";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Aucun id n'etait trouver";
}
?>
