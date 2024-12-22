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
        die("Erreur de connexion : " . $conn->connect_error);
    }

    $queryUpdateRole = "UPDATE users SET role = 'user' WHERE id = ?";
    $stmt = $conn->prepare($queryUpdateRole);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo "ce admin est maitenant un user";
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