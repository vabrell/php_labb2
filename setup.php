<?php
require_once('layouts/header.php');


$faker = Faker\Factory::create('sv_SE');

$db = new App\Database;

// Get the predefined SQL query to setup the database
$db_file = ROOT . 'setup/database.sql';
$db_sql = fopen($db_file, 'r');
$sql = fread($db_sql, filesize($db_file));
fclose($db_sql);

try {
    // Create all database tables
    $db->conn->exec($sql);

    // Create the default admin user
    App\User::create('Admin', 'Adminsson', 'admin', 'password');

    // Create 50 members
    for ($i = 0; $i < 50; $i++) {
        App\Member::create($faker->firstName(), $faker->lastName);
    }

    // Print success message
    echo "<div class='text-success'>Databas konstruktionen är nu klar!</div>";
} catch (PDOException $e) {
    // Print exception message
    echo "<div class='text-danger'>{$e->getMessage()}</div>";
}

require_once('layouts/footer.php');
