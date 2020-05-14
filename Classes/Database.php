<?php

class Database
{
    private $config = ROOT . 'config.ini';

    public $conn;

    /**
     * Construct the database connection
     */
    public function __construct()
    {
        // Check if configuration file exists; else throw exception
        try {
            if (!file_exists($this->config)) {
                throw new Exception('Configuration file does not exist.');
            }
        } catch (Exception $e) {
            echo "<div class='text-danger'>$e</div>";
            return;
        }
        
        // Try to parse the configuration file; else throw exception
        try {
            if (!$config = parse_ini_file($this->config, true)) {
                throw new Exception('Could not read configuration file');
            }
        } catch (Exception $e) {
            echo "<div class='text-danger'>$e</div>";
            return;
        }

        // Get all configuration settings as variables
        extract($config['database']);

        // Create the DSN string
        $dsn = "$driver:host=$host;dbname=$dbname";
        
        // Try to connect to the database; else throw exception
        try {
            $this->conn = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo "<div class='text-danger'>{$e->getMessage()}</div>";
            return;
        }
    }
}
