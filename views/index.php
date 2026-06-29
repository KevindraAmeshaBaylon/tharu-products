<?php
// views/index.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tharu & Products - Animal Feed Manufacturing & Supply</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col justify-between">

    <nav class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-black text-green-700 tracking-tight flex items-center">
                    <i class="fa-solid fa-wheat-awn mr-2"></i>THARU & PRODUCTS
                </span>
            </div>
            
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['role'])): ?>
                    <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg border">
                        Logged in as: <strong class="text-green-700"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </span>
                    <a href="auth/logout.php" class="text-xs bg-rose-600 hover:bg-rose-700 text-white font-bold px-4 py-2 rounded-xl transition shadow-sm">
                        Log Out <i class="fa-solid fa-power-off ml-1"></i>
                    </a>
                <?php else: ?>
                    <a href="auth/login.php" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl shadow transition transform active:scale-95 flex items-center">
                        Secure Staff Portal <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <header class="bg-gradient-to-br from-green-800 via-green-900 to-slate-900 text-white py-20 px-6 text-center shadow-md">
        <div class="max-w-3xl mx-auto space-y-6">
            <span class="bg-green-700/50 text-green-300 text-xs font-extrabold px-3 py-1.5 rounded-full uppercase tracking-widest border border-green-500/30">
                Premium Animal Feed Production Line
            </span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight">
                Empowering Livestock Farms Across Sri Lanka
            </h1>
            <p class="text-green-100/80 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                Supplying high-yield commercial nutrition formulas designed for optimum growth ratios in poultry, dairy, and livestock farming sectors.
            </p>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-16 w-full flex-1">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900">Our Bulk Formula Catalogue</h2>
            <p class="text-gray-500 text-sm mt-1">High-grade ingredients traced and processed with structural accuracy.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between hover:shadow-md transition">
                <div class="space-y-3">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 flex items-center justify-center rounded-xl text-xl font-bold shadow-inner">
                        <i class="fa-solid fa-egg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Premium Chicken Feed Mix</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Optimized amino-acid layers formula providing peak laying performance and robust flock bodyweight indexes.
                    </p>
                </div>
                <div class="border-t pt-4 mt-6 flex justify-between items-center text-xs font-mono">
                    <span class="text-gray-400">Packaging Unit:</span>
                    <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">50 KG Net Weight Bag</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between hover:shadow-md transition">
                <div class="space-y-3">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 flex items-center justify-center rounded-xl text-xl font-bold shadow-inner">
                        <i class="fa-solid fa-cow"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Dairy Cattle Growth Feed</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Enriched mineral and fiber compositions to enhance daily commercial milk output quantities smoothly.
                    </p>
                </div>
                <div class="border-t pt-4 mt-6 flex justify-between items-center text-xs font-mono">
                    <span class="text-gray-400">Packaging Unit:</span>
                    <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">50 KG Net Weight Bag</span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between hover:shadow-md transition">
                <div class="space-y-3">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 flex items-center justify-center rounded-xl text-xl font-bold shadow-inner">
                        <i class="fa-solid fa-piggy-bank"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Swine Growth Formulation</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        High energy intake composition providing excellent grain conversion percentages for livestock outlets.
                    </p>
                </div>
                <div class="border-t pt-4 mt-6 flex justify-between items-center text-xs font-mono">
                    <span class="text-gray-400">Packaging Unit:</span>
                    <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">50 KG Net Weight Bag</span>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-slate-900 text-slate-400 text-xs py-6 border-t border-slate-800 text-center">
        <p>&copy; 2026 Tharu & Products Supply Systems Management Platform. All Rights Reserved.</p>
        <p class="text-[10px] text-slate-600 mt-1 font-mono">NIBM Final Evaluation Project Submission Blueprint</p>
    </footer>

</body>
</html>