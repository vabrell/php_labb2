<?php

class User extends Model
{
    protected static $table = 'users';
    protected static $columns = ['id', 'firstName', 'lastName', 'username'];

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
}
