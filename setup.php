<?php

require_once('layouts/head.php');

$db = new Database;

// Get the predefined SQL query to setup the database
$db_file = ROOT . 'setup/database.sql';
$db_sql = fopen($db_file, 'r');
$sql = fread($db_sql, filesize($db_file));
fclose($db_sql);

try {
    // Create all database tables
    $db->conn->exec($sql);

    // Print success message
    echo "<div class='text-success'>Databas konstruktionen är nu klar!</div>";
} catch (PDOException $e) {
    // Print exception message
    echo "<div class='text-danger'>{$e->getMessage()}</div>";
}

require_once('layouts/footer.php');
