<?php

class Model
{
    protected static $table;
    protected static $columns = [];
    protected static $where;

    /**
     * Fetch all records from the database
     *
     * @return Array Class objects
     */
    public static function all()
    {
        $db = new Database;

        $columns = implode(', ', static::$columns);
        $table = static::$table;
        $sql = "SELECT $columns FROM $table";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class());
    }

    /**
     * Fetch record by id
     *
     * @param Int $id
     *
     * @return Class Object
     */
    public static function find(Int $id)
    {
        $db = new Database;

        $columns = implode(', ', static::$columns);
        $table = static::$table;
        $sql = "SELECT $columns FROM $table WHERE id = :id";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetchObject(get_class());
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
        static::$where[] = [
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

        $columns = implode(', ', static::$columns);
        $table = static::$table;
        $sql = "SELECT $columns FROM $table";

        foreach (static::$where as $index => $where) {
            if ($index === 0) {
                $sql .= ' WHERE ';
            } else {
                $sql .= ' AND ';
            }

            $sql .= "{$where['column']} {$where['operator']} '{$where['needle']}'";
        }

        $stmt = $db->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, get_class());
    }
}
