<?php
// Démarrage de la session
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "securite");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérification si l'utilisateur est connecté et est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Récupérer le nombre d'utilisateurs créés aujourd'hui
$resultNewUsers = $conn->query("SELECT COUNT(*) AS total FROM users WHERE DATE(created_at) = CURDATE()");
$totalNewUsersToday = $resultNewUsers->fetch_assoc()['total'];

// Récupérer les utilisateurs en attente de validation
$resultPendingUsers = $conn->query("SELECT * FROM users WHERE status = 'pending'");

// Si un utilisateur doit être archivé
if (isset($_POST['archive'])) {
    $userId = $_POST['archive'];
    $conn->query("UPDATE users SET archived = 1 WHERE id = $userId");
    echo "<script>alert('Utilisateur archivé avec succès.'); window.location.href = 'dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-bold mb-4">Tableau de bord Admin</h1>

            <!-- Statistiques -->
            <div class="mb-4">
                <p class="text-lg">Nouveaux utilisateurs aujourd'hui : <strong><?php echo $totalNewUsersToday; ?></strong></p>
            </div>

            <!-- Validation des utilisateurs -->
            <div>
                <h2 class="text-xl font-bold mb-2">Utilisateurs en attente</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Email</th>
                            <th class="border p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $resultPendingUsers->fetch_assoc()): ?>
                        <tr>
                            <td class="border p-2"><?php echo $user['id']; ?></td>
                            <td class="border p-2"><?php echo $user['email']; ?></td>
                            <td class="border p-2">
                                <a href="accept.php?id=<?php echo $user['id']; ?>" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Approuver</a>
                                <a href="reject.php?id=<?php echo $user['id']; ?>" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Rejeter</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Archiver un utilisateur -->
            <div class="mt-6">
                <h2 class="text-xl font-bold mb-2">Archiver un utilisateur</h2>
                <form method="POST">
                    <label for="archive" class="block mb-2">ID de l'utilisateur :</label>
                    <input type="number" id="archive" name="archive" class="border rounded p-2 w-full mb-4">
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Archiver</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
