<?php

namespace App;

use App\Database;

class Model
{
    protected static $table;
    protected static $columns = [];
    protected static $where;
    protected static $orderBy = 'id';
    protected static $order = 'ASC';

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

        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
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
     * Order by column
     * 
     * @param String $column Column to order by
     * @param String $order Optional - ASC, DESC
     * 
     * @return this
     */
    public function orderBy(String $column, String $order = null)
    {
        static::$orderBy = $column;
        if ($order) {
            static::$order = $order;
        }

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

        if (static::$where) {
            foreach (static::$where as $index => $where) {
                if ($index === 0) {
                    $sql .= ' WHERE ';
                } else {
                    $sql .= ' AND ';
                }

                $sql .= "{$where['column']} {$where['operator']} '{$where['needle']}'";
            }
        }

        $orderBy = static::$orderBy;
        $order = static::$order;
        $sql .= " ORDER BY $orderBy $order";

        $stmt = $db->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_CLASS, get_class());
    }
}
