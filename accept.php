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

    $query = "UPDATE users SET role = 'admin' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "ce user est maitenant un admin";
        header("Refresh: 0; url=dashboard.php");
    } else {
        echo "Erreur : un probleme lors du update";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Aucun id n'etait trouver";
}
?>