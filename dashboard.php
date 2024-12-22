<?php
$conn = new mysqli("localhost", "root", "", "securite");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
$queryUsers = "SELECT id, email, status_id FROM users";
$resultUsers = $conn->query($queryUsers);

if (!$resultUsers) {
    die("Erreur SQL : " . $conn->error);
}

if ($resultUsers->num_rows == 0) {
    echo "Aucun utilisateur en attente trouvé.<br>";
}

$usersData = [];
while ($user = $resultUsers->fetch_assoc()) {




    $userId = $user['id'];
    $queryDetails = "SELECT first_name, last_name, phone_number FROM user_details WHERE user_id = $userId";
    $resultDetails = $conn->query($queryDetails);

    if ($resultDetails && $userDetails = $resultDetails->fetch_assoc()) {
        $user['first_name'] = $userDetails['first_name'];
        $user['last_name'] = $userDetails['last_name'];
        $user['phone_number'] = $userDetails['phone_number'];
        $usersData[] = $user;
    } else {
        echo "Détails manquants pour l'utilisateur ID = " . $user['id'] . "<br>";
    }
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
                <h2 class="text-xl font-bold mb-2">Utilisateurs enregistres</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border p-2">ID</th>
                            <th class="border p-2">Prenom</th>
                            <th class="border p-2">Nom</th>
                            <th class="border p-2">Email</th>
                            <th class="border p-2">Numero de telephone</th>
                            <th class="border p-2">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($usersData)): ?>
                            <?php foreach ($usersData as $user): ?>
                                <tr>
                                    <td class="border p-2"><?php echo $user['id']; ?></td>
                                    <td class="border p-2"><?php echo $user['first_name']; ?></td>
                                    <td class="border p-2"><?php echo $user['last_name']; ?></td>
                                    <td class="border p-2"><?php echo $user['email']; ?></td>
                                    <td class="border p-2"><?php echo $user['phone_number']; ?></td>
                                    <td class="border p-2">
                                        <a href="accept.php?id=<?php echo $user['id']; ?>"
                                            class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">UPDATE TO ADMIN</a>
                                        <a href="reject.php?id=<?php echo $user['id']; ?>"
                                            class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">UPDATE TO USER</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="border p-2">Aucun utilisateur trouvé en attente.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>