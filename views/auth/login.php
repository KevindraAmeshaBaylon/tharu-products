<?php
// views/auth/login.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect shortcut: If already logged in, route directly from the auth subfolder
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'Owner': header("Location: ../dashboards/owner.php"); exit();
        case 'Accountant': header("Location: ../dashboards/accountant.php"); exit();
        case 'Stock Supervisor': header("Location: ../dashboards/stock_supervisor.php"); exit();
        case 'Sales Supervisor': header("Location: ../dashboards/sales_supervisor.php"); exit();
        case 'Driver': header("Location: ../dashboards/driver.php"); exit();
        case 'Worker': header("Location: ../dashboards/worker.php"); exit();
    }
}

// Go up two folders to leave 'auth' and 'views', then reach 'app/'
include_once __DIR__ . '/../../app/controllers/AuthController.php';

$authController = new AuthController();
$errorMessage = $authController->handleLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tharu & Products - Secure Staff Access Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col justify-center items-center px-4 font-sans antialiased">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden transform transition duration-500">
        <!-- Brand Header Section -->
        <div class="bg-gradient-to-br from-green-800 via-green-900 to-slate-900 p-8 text-center text-white relative">
            <div class="absolute top-4 left-4">
                <a href="../index.php" class="text-green-300 hover:text-white transition text-sm flex items-center space-x-1 font-semibold">
                    <i class="fa-solid fa-arrow-left text-xs"></i> <span>Home</span>
                </a>
            </div>
            <div class="w-16 h-16 bg-green-700/30 text-green-400 mx-auto rounded-2xl flex items-center justify-center text-2xl border border-green-500/20 shadow-inner mb-3">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2 class="text-2xl font-black tracking-tight">Staff Control Portal</h2>
            <p class="text-xs text-green-200/70 mt-1 uppercase tracking-widest font-semibold">Tharu & Products Enterprise Node</p>
        </div>

        <!-- Verification Notification Banner -->
        <?php if (!empty($errorMessage)): ?>
            <div class="bg-rose-50 border-b border-rose-200 text-rose-700 p-4 text-xs font-bold flex items-center space-x-2 animate-shake">
                <i class="fa-solid fa-triangle-exclamation text-base text-rose-500"></i>
                <span><?php echo htmlspecialchars($errorMessage); ?></span>
            </div>
        <?php endif; ?>

        <!-- Active Credentials Entry Form -->
        <form action="login.php" method="POST" class="p-8 space-y-5">
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-1.5">User Identity Token</label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-user-tag text-sm"></i>
                    </div>
                    <input type="text" name="username" required autocomplete="username"
                           class="w-full border border-gray-200 pl-10 pr-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent font-medium text-gray-800 transition placeholder-gray-400 text-sm" 
                           placeholder="Enter username (e.g., owner)">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider mb-1.5">Security Encryption Pass</label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-key text-sm"></i>
                    </div>
                    <input type="password" name="password" required autocomplete="current-password"
                           class="w-full border border-gray-200 pl-10 pr-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent font-medium text-gray-800 transition placeholder-gray-400 text-sm" 
                           placeholder="••••••••">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-green-700 hover:bg-green-800 text-white font-extrabold py-3.5 rounded-xl shadow-lg hover:shadow-xl transition transform active:scale-[0.98] flex items-center justify-center space-x-2 tracking-wide text-sm">
                    <span>Authenticate Account</span>
                    <i class="fa-solid fa-arrow-right-to-bracket text-xs"></i>
                </button>
            </div>
        </form>

        <!-- Informational Account Matrix Guide for Evaluation Teams -->
        <div class="bg-slate-50 px-8 py-5 border-t border-gray-100 text-[11px] text-gray-500 leading-relaxed">
            <div class="font-bold text-gray-700 mb-1 flex items-center">
                <i class="fa-solid fa-circle-info mr-1 text-slate-400"></i> NIBM Evaluation Testing Kit Profiles:
            </div>
            <div class="grid grid-cols-2 gap-x-4 gap-y-1 font-mono text-[10px] bg-white border p-2 rounded-lg">
                <div>User: <span class="font-bold text-slate-800">owner</span> / owner123</div>
                <div>User: <span class="font-bold text-slate-800">accountant</span> / acc123</div>
                <div>User: <span class="font-bold text-slate-800">stock</span> / stock123</div>
                <div>User: <span class="font-bold text-slate-800">sales</span> / sales123</div>
                <div>User: <span class="font-bold text-slate-800">driver</span> / driver123</div>
                <div>User: <span class="font-bold text-slate-800">worker</span> / worker123</div>
            </div>
        </div>
    </div>

    <div class="text-center mt-6 text-[10px] text-gray-400 font-medium tracking-wide">
        Tharu & Products Distribution Ecosystem System • Security Boundary Core
    </div>

</body>
</html>