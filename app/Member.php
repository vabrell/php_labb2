<?php
namespace App;

use DateTime;

class Member extends Model {
    protected static $table = 'members';
    protected static $columns = ['id', 'firstName', 'lastName', 'membership'];

    private $id;
    private $firstName;
    private $lastName;
    private $membership;

    /**
     * Create a new member
     * 
     * @param String $firstName
     * @param String $lastName
     * 
     * @return Int Affected rows
     */
    public static function create(String $firstName, String $lastName) {
        $db = new Database;

        $table = self::$table;
        $sql = "INSERT INTO $table (firstName, lastName)
                VALUES (:firstName, :lastName)";

        $stmt = $db->conn->prepare($sql);
        return $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName
        ]);
    }

    /**
     * Update a existing member
     * 
     * @param Int $id
     * @param String $firstName
     * @param String $lastName
     * @param String $membership = NULL
     * 
     * @return Int Affected rows
     */
    public static function update(Int $id, String $firstName, String $lastName, String $membership = NULL) {
        $db = new Database;

        $table = self::$table;
        $sql = "UPDATE $table SET
                firstName = :firstName,
                lastName = :lastName,
                membership = :membership
                WHERE id = :id
        ";

        $stmt = $db->conn->prepare($sql);
        return $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':membership' => $membership,
            ':id' => $id
        ]);
    }

    /**
     * Delete a existing member
     * 
     * @param Int $id
     * 
     * @return Int Affected rows
     */
    public static function delete(Int $id)
    {
        $db = new Database;

        $table = self::$table;
        $sql = "DELETE FROM $table
                WHERE id = :id
        ";

        $stmt = $db->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id
        ]);
    }
}