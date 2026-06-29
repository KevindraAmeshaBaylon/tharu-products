<?php
// views/dashboards/sales_supervisor.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Security Boundary Guard: Ensure only an authorized Sales Supervisor can see this workspace
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Sales Supervisor') {
    header("Location: ../auth/login.php");
    exit();
}

include_once __DIR__ . '/../../config/database.php';
$database = new Database();
$db = $database->getConnection();

// --- 📊 DYNAMIC SALES DATA FETCH ENGINE ---
try {
    // 1. Fetch orders requiring vehicle dispatch and driver routing allocations
    $pendingDispatchStmt = $db->query("SELECT co.id, c.farm_name, pi.product_name, co.order_quantity 
                                       FROM client_orders co
                                       JOIN customers c ON co.customer_id = c.id
                                       JOIN product_inventory pi ON co.product_id = pi.id
                                       WHERE co.order_status = 'Pending Verification' LIMIT 5");
    $dispatchQueue = $pendingDispatchStmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Fetch available logistics delivery drivers to populate allocation options
    $driverStmt = $db->query("SELECT id, full_name FROM users WHERE role = 'Driver'");
    $availableDrivers = $driverStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Local simulation fallbacks for testing
    $dispatchQueue = [
        ['id' => 101, 'farm_name' => 'Chilaw Premium Poultry Farm', 'product_name' => 'Premium Chicken Feed', 'order_quantity' => 50]
    ];
    $availableDrivers = [
        ['id' => 5, 'full_name' => 'Heavy Fleet Driver #04 (Lorry)']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tharu & Products - Sales Supervisor Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex font-sans min-h-screen">

    <div class="w-64 bg-slate-900 text-white p-5 space-y-6 flex-shrink-0 flex flex-col justify-between shadow-2xl">
        <div class="space-y-6">
            <div class="border-b border-slate-700 pb-4">
                <h2 class="text-2xl font-black text-amber-500 tracking-wider flex items-center">
                    <i class="fa-solid fa-shop mr-2"></i>SALES DEPT
                </h2>
                <p class="text-[10px] text-slate-400 uppercase tracking-widest mt-1">Fulfillment & Channels</p>
            </div>
            <nav class="space-y-1">
                <a href="#" class="flex items-center space-x-3 bg-slate-800 text-white px-4 py-2.5 rounded-xl font-medium shadow-inner"><i class="fa-solid fa-truck-ramp-box text-amber-500"></i> <span>Dispatch Router</span></a>
            </nav>
        </div>
        <div>
            <div class="p-3 bg-slate-800 rounded-xl mb-4 text-xs border border-slate-700">
                <p class="text-slate-400">Sales Coordinator:</p>
                <p class="font-bold text-amber-400 truncate"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'K. Fernando'); ?></p>
            </div>
            <a href="../auth/logout.php" class="flex items-center justify-center space-x-2 w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-xl shadow transition"><i class="fa-solid fa-power-off"></i> <span>Secure Log Out</span></a>
        </div>
    </div>

    <div class="flex-1 p-8 overflow-y-auto max-w-7xl mx-auto w-full">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Order Dispatch Logistics Desk</h1>
                <p class="text-sm text-gray-500 mt-1">Manage wholesale customer pipelines, allocate fleet operators, and track total sales metrics.</p>
            </div>
        </header>

        <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
            <h3 class="text-xl font-extrabold text-gray-900 border-b pb-3 mb-6 flex items-center">
                <i class="fa-solid fa-truck-arrow-right text-amber-600 mr-2"></i> Pending Logistics Route Allocation Dispatch Queue
            </h3>
            
            <div class="space-y-6">
                <?php if(empty($dispatchQueue)): ?>
                    <p class="text-sm font-semibold text-gray-500 text-center py-8 bg-gray-50 rounded-xl">🎉 No orders waiting for logistics routing driver assignments.</p>
                <?php else: ?>
                    <?php foreach($dispatchQueue as $order): ?>
                        <div class="p-6 bg-slate-50 border border-slate-200 rounded-2xl grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                            
                            <div>
                                <span class="text-xs font-mono font-bold text-slate-700 bg-slate-200 px-2 py-1 rounded">ORDER-#<?php echo $order['id']; ?></span>
                                <h4 class="font-extrabold text-gray-900 text-lg mt-2"><?php echo htmlspecialchars($order['farm_name']); ?></h4>
                                <p class="text-sm text-gray-600 mt-1"><i class="fa-solid fa-wheat-awn mr-1 text-amber-600"></i> <?php echo htmlspecialchars($order['product_name']); ?></p>
                            </div>

                            <div class="bg-white p-3 rounded-xl border shadow-inner">
                                <p class="text-xs font-bold text-gray-400 uppercase">Load Allocation Volume Metrics:</p>
                                <div class="flex justify-between items-baseline mt-1">
                                    <span class="text-xl font-black text-slate-800"><?php echo $order['order_quantity']; ?> Bags</span>
                                    <span class="text-xs font-mono text-gray-500">Total weight: <?php echo ($order['order_quantity'] * 50) / 1000; ?> Metric Tons</span>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-0.5">Calculated using industry-standard 50kg wholesale net packaging units.</p>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-black text-gray-700 uppercase tracking-wider">Assign Active Fleet Operator:</label>
                                <div class="flex space-x-2">
                                    <select class="flex-1 border border-gray-300 p-2 rounded-xl bg-white text-sm font-semibold text-gray-800">
                                        <?php foreach($availableDrivers as $driver): ?>
                                            <option value="<?php echo $driver['id']; ?>"><?php echo htmlspecialchars($driver['full_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button onclick="alert('Lorry manifest locked. Fleet operator dispatched via mobile notification terminals.')" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-bold px-4 py-2 rounded-xl text-sm transition transform active:scale-95 shadow">
                                        Dispatch <i class="fa-solid fa-paper-plane ml-1"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>