<?php

function requireRole(string ...$allowed): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'], $_SESSION['role']) || !in_array($_SESSION['role'], $allowed, true)) {
        header('Location: ../auth/login.php');
        exit;
    }
}
