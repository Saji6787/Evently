<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('require_role')) {
    function require_role($role_required) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role_required) {
            header("Location: login.php");
            exit;
        }
    }
}
