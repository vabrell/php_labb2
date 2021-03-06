<?php

namespace App;

class Member extends Model
{
    protected static $table = 'members';
    protected static $columns = ['id', 'firstName', 'lastName', 'membership'];

    public $id;
    public $firstName;
    public $lastName;
    public $membership;

    /**
     * Get all teams for member
     * 
     * @return Array List of Team objects
     */
    public function teams()
    {
        $tm = new TeamsMembers;
        $_teams = $tm->where('member_id', '=', $this->id)->get();
        $teams = [];


        foreach ($_teams as $team) {
            array_push($teams, Team::find($team->team_id));
        }

        return $teams;
    }

    /**
     * Create a new member
     * 
     * @param String $firstName
     * @param String $lastName
     * 
     * @return Int Last inserted ID
     */
    public static function create(String $firstName, String $lastName)
    {
        $db = new Database;

        $table = self::$table;
        $sql = "INSERT INTO $table (firstName, lastName)
                VALUES (:firstName, :lastName)";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            ':firstName' => $firstName,
            ':lastName' => $lastName
        ]);

        return $db->conn->lastInsertId();
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
    public static function update(Int $id, String $firstName, String $lastName, String $membership = NULL)
    {
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

        // Remove all teams_members connections
        TeamsMembers::removeByMember($id);

        $stmt = $db->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id
        ]);
    }
}
