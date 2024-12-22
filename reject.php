<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "securite");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifier si l'ID de l'utilisateur est passé dans l'URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Mettre à jour le statut de l'utilisateur à "rejeté"
    $query = "UPDATE users SET status_id = (SELECT id FROM status WHERE name = 'rejected') WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Rediriger vers le tableau de bord après le rejet
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Erreur lors du rejet de l'utilisateur.";
    }
} else {
    echo "Aucun utilisateur trouvé.";
}
?>
