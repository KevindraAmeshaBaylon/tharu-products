<?php
// views/dashboards/worker.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Security Boundary Guard: Ensure only an authorized Worker can see this workspace
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Worker') {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tharu & Products - Milling Station Floor Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-zinc-900 text-zinc-100 font-sans min-h-screen flex flex-col justify-between">

    <!-- Mobile Header -->
    <header class="bg-zinc-800 border-b border-zinc-700 p-4 sticky top-0 z-50 flex justify-between items-center shadow-md">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-helmet-safety text-yellow-500 text-xl"></i>
            <div>
                <h1 class="font-black text-sm tracking-wide uppercase">Milling Station Floor</h1>
                <p class="text-[10px] text-zinc-400">Worker: <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Production Operator #12'); ?></p>
            </div>
        </div>
        <a href="../auth/logout.php" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition">
            <i class="fa-solid fa-power-off"></i>
        </a>
    </header>

    <!-- Main Workspace Panel -->
    <main class="p-4 max-w-md mx-auto w-full flex-1 space-y-4">
        
        <!-- Attendance Status Block -->
        <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-5 shadow-lg text-center space-y-4">
            <div class="w-16 h-16 bg-zinc-700 rounded-full flex items-center justify-center mx-auto border-2 border-zinc-600 text-2xl">
                <i class="fa-solid fa-clock-with-seconds text-zinc-400"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-white">Daily Production Attendance</h2>
                <p class="text-xs text-zinc-400 mt-1">Clock in your active shift hours below to log automatic operational tokens onto the Accountant's wage ledger.</p>
            </div>
            
            <div class="grid grid-cols-2 gap-3 pt-2">
                <button id="clockInBtn" onclick="this.disabled=true; this.classList.add('opacity-50'); document.getElementById('clockOutBtn').disabled=false; document.getElementById('clockOutBtn').classList.remove('opacity-50'); alert('Shift attendance activated. Accountant ledger synced.');" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition text-sm flex items-center justify-center space-x-1.5 shadow">
                    <i class="fa-solid fa-right-to-bracket"></i> <span>Clock In</span>
                </button>
                <button id="clockOutBtn" onclick="this.disabled=true; this.classList.add('opacity-50'); document.getElementById('clockInBtn').disabled=false; document.getElementById('clockInBtn').classList.remove('opacity-50'); alert('Shift attendance terminated successfully.');" class="bg-zinc-700 hover:bg-zinc-600 text-zinc-300 font-bold py-3 rounded-xl transition text-sm flex items-center justify-center space-x-1.5 shadow opacity-50" disabled>
                    <i class="fa-solid fa-right-from-bracket"></i> <span>Clock Out</span>
                </button>
            </div>
        </div>

        <!-- Task Advisory Card -->
        <div class="bg-zinc-800 border border-zinc-700 rounded-2xl p-5 shadow-lg space-y-3">
            <h3 class="text-xs font-black uppercase text-yellow-500 tracking-wider flex items-center">
                <i class="fa-solid fa-circle-exclamation mr-1.5 text-base"></i> Active Plant Assignment Rules
            </h3>
            <ul class="text-xs text-zinc-400 space-y-2.5 list-inside list-disc leading-relaxed">
                <li>Verify structural container valve locks prior to executing feed mill operations.</li>
                <li>Report finished bag production targets straight to the Stock Supervisor desk for intake records.</li>
            </ul>
        </div>
    </main>

    <footer class="p-3 text-[10px] text-zinc-500 text-center border-t border-zinc-800">
        Tharu & Products • Plant Operational Shift Interface
    </footer>

</body>
</html>