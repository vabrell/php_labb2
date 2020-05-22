<?php

namespace App;

use App\TeamsMembers;
use App\Member;
use App\Activity;

class Team extends Model
{
    protected static $table = 'teams';
    protected static $columns = ['id', 'name', 'activity_id'];

    public $id;
    public $name;

    /**
     * Get all team members
     * 
     * @return Array List of Member objects
     */
    public function members() {
        $tm = new TeamsMembers;
        $_members = $tm->where('team_id', '=', $this->id)->get();
        $members = [];

        foreach ($_members as  $member) {
            array_push($members, Member::find($member->id));
        }

        return $members;
    }

    /**
     * Get activity
     * 
     * @return Object App\Activity
     */
    public function activity()
    {
        return Activity::find($this->activity_id);
    }

    /**
     * Get the member count for team
     */
    public function memberCount() {
        $tm = new TeamsMembers;
        $members = $tm->where('team_id', '=', $this->id)->get();

        return count($members);
    }

    /**
     * Create a new team
     * 
     * @param String $name
     * @param Int $activity_id
     * 
     * @return Int Last inserted ID
     */
    public static function create(String $name, Int $activity_id)
    {
        $db = new Database;

        $table = self::$table;
        $sql = "INSERT INTO $table (name, activity_id)
                VALUES (:name, :activity_id)";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':activity_id' => $activity_id
        ]);

        return $db->conn->lastInsertId();
    }

    /**
     * Update a existing team
     * 
     * @param Int $id
     * @param String $name
     * @param Int $activity_id
     * 
     * @return Int Affected rows
     */
    public static function update(Int $id, String $name, Int $activity_id)
    {
        $db = new Database;

        $table = self::$table;
        $sql = "UPDATE $table SET
                name = :name,
                activity_id = :activity_id
                WHERE id = :id
        ";

        $stmt = $db->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':activity_id' => $activity_id,
            ':id' => $id
        ]);
    }

    /**
     * Delete a existing team
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
