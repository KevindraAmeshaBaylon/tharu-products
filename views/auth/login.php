<?php
// views/auth/login.php

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../app/controllers/AuthController.php';

$database = new Database();
$db = $database->getConnection();

$authController = new AuthController($db);
$errorMessage = $authController->login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tharu & Products - Secure Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-200">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-extrabold text-green-700 tracking-tight"><i class="fa-solid fa-wheat-awn mr-2"></i>THARU</h1>
            <p class="text-gray-500 text-sm mt-1">Animal Feed Supplying Enterprise System</p>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4 text-center">🔒 Access Authentication Gateway</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm font-semibold flex items-center">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Username Reference</label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" placeholder="e.g., owner_tharu, accountant_desk" class="w-full pl-10 pr-3 py-2 border rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-600 focus:outline-none transition" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700">Account Password</label>
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" placeholder="••••••••" class="w-full pl-10 pr-3 py-2 border rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-green-600 focus:outline-none transition" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition transform active:scale-95">
                Secure Sign In <i class="fa-solid fa-arrow-right-to-bracket ml-1"></i>
            </button>
        </form>

        <div class="mt-6 border-t pt-4 text-center">
            <p class="text-xs text-gray-400">Testing Reference: Use seeded accounts with simple password '12345'</p>
        </div>
    </div>

</body>
</html>