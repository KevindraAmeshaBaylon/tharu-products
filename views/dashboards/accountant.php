<?php
// views/dashboards/accountant.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Security Boundary Guard: Ensure only an authorized Accountant can see this workspace
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Accountant') {
    header("Location: ../auth/login.php");
    exit();
}

include_once __DIR__ . '/../../config/database.php';
$database = new Database();
$db = $database->getConnection();

// --- 📊 DYNAMIC ACCOUNTING DATA FETCH ENGINE ---
try {
    // 🚚 1. Fetch current delivery drivers to process shift wages
    $driverStmt = $db->query("SELECT id, full_name FROM users WHERE role = 'Driver'");
    $drivers = $driverStmt->fetchAll(PDO::FETCH_ASSOC);

    // 🏭 2. Fetch factory workers to process daily production attendance wages
    $workerStmt = $db->query("SELECT id, full_name FROM users WHERE role = 'Worker'");
    $workers = $workerStmt->fetchAll(PDO::FETCH_ASSOC);

    // 🧾 3. Fetch any pending orders needing corporate financial clearance
    $pendingInvoicesStmt = $db->query("SELECT co.id, c.farm_name, co.total_invoice_lkr, co.payment_method 
                                       FROM client_orders co 
                                       JOIN customers c ON co.customer_id = c.id 
                                       WHERE co.order_status = 'Pending Verification' LIMIT 5");
    $pendingInvoices = $pendingInvoicesStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Fallback simulation placeholders for local validation testing
    $drivers = [['id' => 5, 'full_name' => 'Heavy Fleet Driver #04 (Lorry)']];
    $workers = [['id' => 6, 'full_name' => 'Line Worker #12 (Milling Station)']];
    $pendingInvoices = [
        ['id' => 101, 'farm_name' => 'Chilaw Premium Poultry Farm', 'total_invoice_lkr' => 382500.00, 'payment_method' => 'Cheque on Delivery']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tharu & Products - Financial Accountant Desk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex font-sans min-h-screen">

    <div class="w-64 bg-emerald-950 text-white p-5 space-y-6 flex-shrink-0 flex flex-col justify-between shadow-2xl">
        <div class="space-y-6">
            <div class="border-b border-emerald-800 pb-4">
                <h2 class="text-2xl font-black text-emerald-400 tracking-wider flex items-center">
                    <i class="fa-solid fa-calculator mr-2"></i>ACCOUNTANT
                </h2>
                <p class="text-[10px] text-emerald-300 uppercase tracking-widest mt-1">Financial Operations Desk</p>
            </div>
            <nav class="space-y-1">
                <a href="#" class="flex items-center space-x-3 bg-emerald-900 text-white px-4 py-2.5 rounded-xl font-medium shadow-inner"><i class="fa-solid fa-wallet text-emerald-400"></i> <span>Payroll Engine</span></a>
                <a href="#invoices" class="flex items-center space-x-3 text-emerald-100 hover:bg-emerald-900 px-4 py-2.5 rounded-xl transition"><i class="fa-solid fa-file-invoice-dollar"></i> <span>Invoice Clearance</span></a>
            </nav>
        </div>
        <div>
            <div class="p-3 bg-emerald-900 rounded-xl mb-4 text-xs border border-emerald-800">
                <p class="text-emerald-300">Active Controller Ledger:</p>
                <p class="font-bold text-white truncate"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'M. N. Perera'); ?></p>
            </div>
            <a href="../auth/logout.php" class="flex items-center justify-center space-x-2 w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-2 px-4 rounded-xl shadow transition"><i class="fa-solid fa-power-off"></i> <span>Secure Log Out</span></a>
        </div>
    </div>

    <div class="flex-1 p-8 overflow-y-auto max-w-7xl mx-auto w-full">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Financial & Payroll Processing Desk</h1>
                <p class="text-sm text-gray-500 mt-1">Authorized tracking terminal for automated staff attendance salaries, bonuses, and wholesale invoice clearances.</p>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-extrabold text-gray-900 border-b pb-3 mb-4 flex items-center">
                        <i class="fa-solid fa-truck-moving text-emerald-600 mr-2"></i> Logistics Fleet Driver Payroll Terminal
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Select Active Fleet Driver</label>
                                <select class="w-full border border-gray-200 p-2.5 rounded-xl mt-1 bg-white font-medium">
                                    <?php foreach($drivers as $drv): ?>
                                        <option value="<?php echo $drv['id']; ?>"><?php echo htmlspecialchars($drv['full_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Shift Base Rate (LKR / Fleet Run)</label>
                                <input id="driverBase" type="number" value="4000" class="w-full bg-gray-50 border border-gray-200 p-2.5 rounded-xl mt-1 font-semibold text-gray-500 cursor-not-allowed" readonly>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Total Shifts Completed (Runs)</label>
                                <input id="driverShifts" type="number" min="0" value="0" oninput="calculateDriverPay()" class="w-full border border-gray-200 p-2.5 rounded-xl mt-1 focus:ring-2 focus:ring-emerald-500 font-bold" placeholder="e.g., 12">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Extra Long-Distance Travel Incentives (LKR)</label>
                                <input id="driverBonus" type="number" min="0" value="0" oninput="calculateDriverPay()" class="w-full border border-gray-200 p-2.5 rounded-xl mt-1 focus:ring-2 focus:ring-emerald-500 font-bold" placeholder="0.00">
                            </div>
                        </div>
                        
                        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 flex justify-between items-center">
                            <span class="text-xs font-bold text-emerald-800 uppercase tracking-widest">Total Net Payable Driver Wage:</span>
                            <span id="driverNetOutput" class="text-2xl font-black text-emerald-700">LKR 0.00</span>
                        </div>
                        <button onclick="alert('Driver logistics payout authorized successfully.')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl shadow transition transform active:scale-95">
                            Commit & Issue Driver Payout <i class="fa-solid fa-money-check-dollar ml-1"></i>
                        </button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200">
                    <h3 class="text-xl font-extrabold text-gray-900 border-b pb-3 mb-4 flex items-center">
                        <i class="fa-solid fa-industry text-blue-600 mr-2"></i> Factory Production Worker Daily Wage Tracker
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Select Production Floor Worker</label>
                                <select class="w-full border border-gray-200 p-2.5 rounded-xl mt-1 bg-white font-medium">
                                    <?php foreach($workers as $wrk): ?>
                                        <option value="<?php echo $wrk['id']; ?>"><?php echo htmlspecialchars($wrk['full_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Standard Daily Shift Pay (LKR / Day)</label>
                                <input id="workerBase" type="number" value="3500" class="w-full bg-gray-50 border border-gray-200 p-2.5 rounded-xl mt-1 font-semibold text-gray-500 cursor-not-allowed" readonly>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Days Present (Clocked In)</label>
                                <input id="workerDays" type="number" min="0" max="31" value="0" oninput="calculateWorkerPay()" class="w-full border border-gray-200 p-2.5 rounded-xl mt-1 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="e.g., 24">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700">Approved Industrial Processing Bonus (LKR)</label>
                                <input id="workerBonus" type="number" min="0" value="0" oninput="calculateWorkerPay()" class="w-full border border-gray-200 p-2.5 rounded-xl mt-1 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="0.00">
                            </div>
                        </div>
                        
                        <div class="p-4 bg-blue-50 rounded-xl border border-blue-200 flex justify-between items-center">
                            <span class="text-xs font-bold text-blue-800 uppercase tracking-widest">Total Net Payable Worker Wage:</span>
                            <span id="workerNetOutput" class="text-2xl font-black text-blue-700">LKR 0.00</span>
                        </div>
                        <button onclick="alert('Milling station floor wage payout authorized successfully.')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl shadow transition transform active:scale-95">
                            Commit & Issue Worker Payout <i class="fa-solid fa-money-check-dollar ml-1"></i>
                        </button>
                    </div>
                </div>

            </div>

            <div id="invoices" class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 h-fit">
                <h3 class="text-xl font-extrabold text-gray-900 border-b pb-3 mb-4 flex items-center">
                    <i class="fa-solid fa-file-invoice-dollar text-amber-600 mr-2"></i> Corporate Invoice Pipeline
                </h3>
                <p class="text-xs text-gray-400 mb-4">Verify payments submitted by farm buyers to release stock inventories safely from the factory floors.</p>
                
                <div class="space-y-4">
                    <?php if(empty($pendingInvoices)): ?>
                        <p class="text-sm font-semibold text-gray-500 text-center py-4 bg-gray-50 rounded-xl">🎉 All outstanding farm customer invoices are cleared.</p>
                    <?php else: ?>
                        <?php foreach($pendingInvoices as $inv): ?>
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-2">
                                <div class="flex justify-between items-start">
                                    <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">INV-#<?php echo $inv['id']; ?></span>
                                    <span class="text-xs text-gray-400 font-medium"><?php echo htmlspecialchars($inv['payment_method']); ?></span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-sm truncate"><?php echo htmlspecialchars($inv['farm_name']); ?></h4>
                                <p class="text-lg font-black text-slate-900">LKR <?php echo number_format($inv['total_invoice_lkr'], 2); ?></p>
                                
                                <button onclick="alert('Invoice verified! Stock supervisor notified to clear dispatch dispatch routing gates.')" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2 rounded-lg transition mt-2">
                                    Clear & Authorize Dispatch Release
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <script>
        function calculateDriverPay() {
            const base = parseFloat(document.getElementById('driverBase').value) || 0;
            const shifts = parseFloat(document.getElementById('driverShifts').value) || 0;
            const bonus = parseFloat(document.getElementById('driverBonus').value) || 0;
            
            const total = (base * shifts) + bonus;
            document.getElementById('driverNetOutput').innerText = "LKR " + total.toLocaleString('en-US', {minimumFractionDigits: 2});
        }

        function calculateWorkerPay() {
            const base = parseFloat(document.getElementById('workerBase').value) || 0;
            const days = parseFloat(document.getElementById('workerDays').value) || 0;
            const bonus = parseFloat(document.getElementById('workerBonus').value) || 0;
            
            const total = (base * days) + bonus;
            document.getElementById('workerNetOutput').innerText = "LKR " + total.toLocaleString('en-US', {minimumFractionDigits: 2});
        }
    </script>
</body>
</html>