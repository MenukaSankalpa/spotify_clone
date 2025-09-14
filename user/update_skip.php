<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_id'])) exit();

$user_id = $_SESSION['user_id'];

// increment skip count
$conn->query("UPDATE users 
              SET skip_count = skip_count + 1, last_skip_reset = NOW() 
              WHERE id = $user_id");
