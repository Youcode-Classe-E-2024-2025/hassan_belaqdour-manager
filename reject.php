<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "securite";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Action de rejet
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sql = "UPDATE users SET status = 'rejected' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Erreur lors du rejet de l'utilisateur.";
    }
} else {
    echo "Aucun utilisateur spécifié.";
}
?>
