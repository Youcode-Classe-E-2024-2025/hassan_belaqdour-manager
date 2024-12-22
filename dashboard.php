<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "securite");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les utilisateurs en attente de validation
$query = "SELECT users.id, users.email 
          FROM users 
          JOIN status ON users.status_id = status.id
          WHERE status.name = 'pending'";
$resultPendingUsers = $conn->query($query);

if (!$resultPendingUsers) {
    die("Erreur SQL : " . $conn->error);
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
        </div>
    </div>
</body>
</html>
