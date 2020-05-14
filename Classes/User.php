<?php

class User
{
    private static $table = 'users';
    private static $columns = ['id', 'firstName', 'lastName', 'username'];
    private static $where = [];

    public $id;
    public $firstName;
    public $lastName;
    public $username;

    /**
     * Create a new user
     *
     * @param String $firstName
     * @param String $lastName
     * @param String $username
     * @param String $password
     *
     * @return Mixed Rows affected
     */
    public static function create(String $firstName, String $lastName, String $username, String $password)
    {
        $db = new Database;

        $table = self::$table;
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO $table (firstName, lastName, username, password) VALUES (:firstName, :lastName, :username, :password)";

        $stmt = $db->conn->prepare($sql);
        
        return $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':username' => $username,
            ':password' => $password
        ]);
    }

    /**
     * Update existing user
     *
     * @param Int $id
     * @param String $firstName
     * @param String $lastName
     *
     * @return Mixed Rows affected
     */
    public static function update(Int $id, String $firstName, String $lastName)
    {
        $db = new Database;

        $table = self::$table;

        $sql = "UPDATE $table SET firstName = :firstName, lastName = :lastName WHERE id = :id";

        $stmt = $db->conn->prepare($sql);
        
        return $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':id' => $id
        ]);
    }

    /**
     * Delete existing user
     *
     * @param Int $id
     *
     * @return Mixed Rows affected
     */
    public static function delete(Int $id)
    {
        $db = new Database;

        $table = self::$table;

        $sql = "DELETE FROM $table WHERE id = :id";

        $stmt = $db->conn->prepare($sql);
        
        return $stmt->execute([
            ':id' => $id
        ]);
    }

    /**
     * Fetch all users from the database
     *
     * @return Array User objects
     */
    public static function all()
    {
        $db = new Database;

        $columns = implode(', ', self::$columns);
        $table = self::$table;
        $sql = "SELECT $columns FROM $table";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    /**
     * Fetch user by id
     *
     * @param Int $id
     *
     * @return User Object
     */
    public static function find(Int $id)
    {
        $db = new Database;

        $columns = implode(', ', self::$columns);
        $table = self::$table;
        $sql = "SELECT $columns FROM $table WHERE id = :id";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        
        return $stmt->fetchObject(User::class);
    }

    /**
     * Add a where clause to the query
     *
     * @param String $column
     * @param String $operator =, <, <=, >, >=, LIKE
     * @param String $needle
     *
     * @return this
     */
    public function where(String $column, String $operator, String $needle)
    {
        self::$where[] = [
            'column' => $column,
            'operator' => $operator,
            'needle' => $needle
        ];

        return $this;
    }

    /**
     * Run the query
     *
     * @return Array User objects
     */
    public function get()
    {
        $db = new Database;

        $columns = implode(', ', self::$columns);
        $table = self::$table;
        $sql = "SELECT $columns FROM $table";

        foreach (self::$where as $index => $where) {
            if ($index === 0) {
                $sql .= ' WHERE ';
            } else {
                $sql .= ' AND ';
            }

            $sql .= "{$where['column']} {$where['operator']} '{$where['needle']}'";
        }

        $stmt = $db->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
    }
}
