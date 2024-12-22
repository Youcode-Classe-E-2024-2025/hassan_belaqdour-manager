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




    $queryUpdateRole = "UPDATE users SET role = 'admin' WHERE id = ?";
    $stmt = $conn->prepare($queryUpdateRole);
    $stmt->bind_param("i", $userId);







    $stmt->close();
    $conn->close();
} else {
    echo "aucun utilisateur trouver";
}
?>