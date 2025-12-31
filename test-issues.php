<?php

// SQL Injection
function getUser($id) {
    $query = "SELECT * FROM users WHERE id = " . $id;
    return $query;
}

// XSS vulnerability
function display($name) {
    echo "<h1>" . $name . "</h1>";
}

// Hardcoded password
$password = "admin123";
$api_key = "sk-secret-key-12345";

// Empty catch
try {
    throw new Exception("Error");
} catch (Exception $e) {
}

// Weak hash
function hashPassword($pass) {
    return md5($pass);
}
