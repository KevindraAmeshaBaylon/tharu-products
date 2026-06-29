<?php
// views/auth/logout.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Wipe out all current session context parameters entirely
$_SESSION = array();

// 2. Clear cookie identification tokens if they exist
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the actual system core session process
session_destroy();

// 4. Redirect safely back to the Login workspace
header("Location: login.php");
exit();
?>