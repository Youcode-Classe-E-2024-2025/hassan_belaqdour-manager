<?php
session_start();

// Connexion à la base de données
$host = 'localhost'; // Hôte
$dbname = 'securite'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur de la base de données
$password = ''; // Mot de passe de la base de données (si vide, laisser vide pour XAMPP)

try {
    // Créer une connexion à la base de données avec PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur de connexion, afficher un message
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirm_password) {
        $error_message = "Les mots de passe ne correspondent pas.";
    } else {
        // Hachage du mot de passe avant de l'enregistrer
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Vérifier si l'email existe déjà dans la base de données
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error_message = "Cet email est déjà utilisé.";
        } else {
            // Insérer l'utilisateur dans la base de données
            $stmt = $conn->prepare("INSERT INTO users_details (first_name, last_name, phone_number) VALUES (:first_name, :last_name, :phone_number)");
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':phone_number', $phone_number);
             $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);

            // Par défaut, le rôle est 'user'
            $role = 'user';

            if ($stmt->execute()) {
                // Rediriger vers la page de login après l'inscription réussie
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-8">
        <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Sign Up</h2>

            <?php if (isset($error_message)): ?>
                <div class="mb-4 text-red-500"><?= $error_message; ?></div>
            <?php endif; ?>

            <form action="signup.php" method="POST">
                <div class="mb-4">
                    <label for="first_name" class="block text-gray-700">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-gray-700">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="phone_number" class="block text-gray-700">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="confirm_password" class="block text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Sign Up</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
