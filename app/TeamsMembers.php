<?php

namespace App;

class TeamsMembers extends Model {
    protected static $table = 'teams_members';
    protected static $columns = ['id', 'team_id', 'member_id'];

    public $id;
    public $team_id;
    public $member_id;

    /**
     * Add a member to a team
     * 
     * @param Int $team The id of the team
     * @param Int $member The id of the member
     */
    public static function add(Int $team, Int $member) {
        $db = new Database;

        $table = self::$table;
        $sql = "INSERT INTO $table (team_id, member_id) VALUES (:team, :member)";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            ':team' => $team,
            ':member' => $member
        ]);
    }
}