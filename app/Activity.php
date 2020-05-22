<?php

namespace App;

class Activity extends Model
{
    protected static $table = 'activities';
    protected static $columns = ['id', 'name'];

    public $id;
    public $name;

    /**
     * Get all teams for activity
     * 
     * @return Array List of Team objects
     */
    public function teams()
    {
        $tm = new Team;
        $_teams = $tm->where('activity_id', '=', $this->id)->get();
        $teams = [];


        foreach ($_teams as $team) {
            array_push($teams, Team::find($team->id));
        }

        return $teams;
    }

    /**
     * Member count for activity
     * 
     * @return Int Count of members
     */
    public function memberCount() {
        $count = 0;
        foreach ($this->teams() as $team) {
            $count += $team->memberCount();
        }

        return $count;
    }

    /**
     * Create a new activity
     * 
     * @param String $name
     * 
     * @return Int Last inserted ID
     */
    public static function create(String $name)
    {
        $db = new Database;

        $table = self::$table;
        $sql = "INSERT INTO $table (name)
                VALUES (:name)";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
        ]);

        return $db->conn->lastInsertId();
    }

    /**
     * Update a existing activity
     * 
     * @param Int $id
     * @param String $name
     * 
     * @return Int Affected rows
     */
    public static function update(Int $id, String $name)
    {
        $db = new Database;

        $table = self::$table;
        $sql = "UPDATE $table SET
                name = :name
                WHERE id = :id
        ";

        $stmt = $db->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':id' => $id
        ]);
    }

    /**
     * Delete a existing activity
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
