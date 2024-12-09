<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function requireRole($requiredRole) {
    if (!isLoggedIn() || !hasRole($requiredRole)) {
        header('Location: login.php');
        exit;
    }
}

?>
