<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "securite";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_date = $_POST['appointment_date'];
    $description = $_POST['description'];
    $status_id = 1;
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO appointments (user_id, appointment_date, description, status_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $appointment_date, $description, $status_id);

    if ($stmt->execute()) {
        $message = "Rendez-vous ajouté avec succès !";
    } else {
        $message = "Erreur lors de l'ajout du rendez-vous.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre un Rendez-vous</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Prendre un Rendez-vous</h1>

            <form method="POST">
                <div class="mb-4">
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700">Date du Rendez-vous</label>
                    <input type="date" id="appointment_date" name="appointment_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description (Maladie)</label>
                    <textarea id="description" name="description" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Envoyer</button>
            </form>

            <?php if (isset($message)): ?>
                <p class="mt-4 text-center text-green-500"><?php echo $message; ?></p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
