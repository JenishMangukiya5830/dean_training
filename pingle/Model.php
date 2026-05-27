<?php

/**
 * Pingle
 * 
 *
 * @copyright 2014 The Pingle.
 * @link www.thepingle.com
 * @author Faiz Rasool <faiz708@gmail.com>
 */

namespace Pingle\Model;

class Model {

    protected $storage;

    //database connection
    public function __construct(Storage $storage, \PDO $db) {
        $this->storage = $storage;
        $this->storage->db = $db;
    }

    //create new Transaction
    public function startTransaction() {
        $this->storage->transaction = true;
        $this->storage->db->beginTransaction();
    }

    //select the table
    function Table($table) {
        $this->storage->table = (string) $table;
        return $this;
    }

    //select specfic columns
    function selectColumns(Array $Colomns) {
        $this->storage->columns = $Colomns;
        return $this;
    }

    //select all columns
    function select() {
        $this->storage->columns = "*";
        return $this;
    }

    function getRowCount($name) {
        $this->storage->action = "Count";
        $this->storage->columns = "SELECT count(*) as $name";
        return $this;
    }

    function getAverage($column, $name) {
        $this->storage->action = "Average";
        $this->storage->columns = "SELECT AVG(`$column`) as $name";
        return $this;
    }

    function getSum($column, $name) {
        $this->storage->action = "Sum";
        $this->storage->columns = "SELECT SUM(`$column`) as $name";
        return $this;
    }

    //Delete
    function Delete() {
        $this->storage->action = "Delete";
        return $this;
    }

    //Insert
    function Insert(Array $Data) {
        $this->storage->action = "Insert";
        $this->storage->Data = $Data;
        return $this;
    }

    //Multi Insert
    function multiInsert(Array $Header, Array $Data) {
        $this->storage->action = "Multi Insert";
        $this->storage->Data = $Data;
        $this->storage->Header = $Header;
        return $this;
    }

    //Update
    function Update(Array $Data) {
        $this->storage->action = "Update";
        $this->storage->Data = $Data;
        return $this;
    }

    //add Where
    function Where($key, $value, $operation, $backtrick = true) {
        $this->storage->where = array($key, $this->storage->hex($value), $operation, $backtrick);
        return $this;
    }

    //and Where
    function andWhere($key, $value, $operation, $backtrick = true) {
        $this->storage->andWhere[] = array($key, $this->storage->hex($value), $operation, $backtrick);
        return $this;
    }

    //or where
    function orWhere($key, $value, $operation, $backtrick = true) {
        $this->storage->orWhere[] = array($key, $this->storage->hex($value), $operation, $backtrick);
        return $this;
    }

    //In Where
    function in($key, $value) {
        $this->storage->in = array($key, $value);
        return $this;
    }

    //In and Where
    function andIn($key, $value) {
        $this->storage->andInWhere[] = array($key, $value);
        return $this;
    }

    //In or where
    function orIn($key, $value) {
        $this->storage->orInWhere[] = array($key, $value);
        return $this;
    }

    //set limit
    function limit($limit, $offset = 0) {
        $this->storage->limit = (int) $limit;
        $this->storage->offset = (int) $offset;
        return $this;
    }

    //sort the table
    function orderBy($field, $order) {
        $this->storage->orderBy = array($field, $order);
        return $this;
    }

    //group the table
    function groupBy($field) {
        $this->storage->groupby = $field;
        return $this;
    }

    //get the last insert id
    function lastInsertId() {
        return $this->storage->db->lastInsertId();
    }

    //find the affected row
    function affectedRows() {
        return $this->storage->db->affected_rows();
    }

    //empty the table
    function emptyTable($table) {
        $sql = "DELETE FROM `$table`";
        $result = $this->storage->db->prepare($sql);
        $result->execute();
        return $result;
    }

    //get the SQL statement for the query
    function getSQL() {

        $this->storage->query = "";

        //Select Specfic Colomns
        if (is_array($this->storage->columns)) {
            $this->storage->query = "SELECT `" . implode("`,`", $this->storage->columns) . "` FROM";
        }

        //Select All
        if ($this->storage->columns == "*") {
            $this->storage->query = "SELECT * FROM";
        }

        //Count OR SUM OR Average
        if ($this->storage->action == "Count" || $this->storage->action == "Sum" || $this->storage->action == "Average") {
            $this->storage->query = $this->storage->columns . " FROM";
        }

        //Delete
        if ($this->storage->action == "Delete") {
            $this->storage->query = "DELETE FROM";
        }

        //Insert
        if ($this->storage->action == "Insert") {
            $this->storage->query = "INSERT";
        }

        //Multi Insert
        if ($this->storage->action == "Multi Insert") {
            $this->storage->query = "INSERT INTO";
        }

        //Update
        if ($this->storage->action == "Update") {
            $this->storage->query = "UPDATE";
        }

        //Table
        if ($this->storage->table == "") {
            return "No table Selected";
        } else {
            $this->storage->query .= " `{$this->storage->table}`";
        }

        //Multi Insert
        if ($this->storage->action == "Multi Insert") {
            $query = "";
            foreach ($this->storage->Data as $data) {
                $query .= "(";
                foreach ($data as $value) {
                    $query .= "UNHEX('" . $this->storage->hex($value) . "'), ";
                }
                $query = trim($query);
                $query = trim($query, ",");
                $query .= "), ";
            }
            $query = trim($query);
            $query = trim($query, ",");
            $this->storage->query .= " (`" . implode("`, `", $this->storage->Header) . "`) VALUES $query";
        }

        //Insert or Update
        if ($this->storage->action == "Insert" || $this->storage->action == "Update") {
            $this->storage->query .= " SET";
        }

        //Insert or Update Data
        if (is_array($this->storage->Data) && $this->storage->action != "Multi Insert") {
            $sql = "";
            foreach ($this->storage->Data as $key => $value) {
                if ($sql == "") {
                    $sql.=" `" . $key . "` = UNHEX('" . $this->storage->hex($value) . "')";
                } else {
                    $sql.=", `" . $key . "` = UNHEX('" . $this->storage->hex($value) . "')";
                }
            }
            $this->storage->query .= $sql;
        }

        //Where
        if (is_array($this->storage->where)) {
            if ($this->storage->where[3]) {
                $backtricks = "`";
            } else {
                $backtricks = " ";
            }
            $this->storage->query .= " WHERE {$backtricks}{$this->storage->where[0]}{$backtricks} {$this->storage->where[2]} UNHEX('{$this->storage->where[1]}')";
            if (is_array($this->storage->andWhere)) {
                foreach ($this->storage->andWhere as $where) {
                    if ($where[3]) {
                        $backtricks = "`";
                    } else {
                        $backtricks = " ";
                    }
                    $this->storage->query .= " AND {$backtricks}{$where[0]}{$backtricks} {$where[2]} UNHEX('{$where[1]}')";
                }
            }
            if (is_array($this->storage->orWhere)) {
                foreach ($this->storage->orWhere as $where) {
                    if ($where[3]) {
                        $backtricks = "`";
                    } else {
                        $backtricks = " ";
                    }
                    $this->storage->query .= " OR {$backtricks}{$where[0]}{$backtricks} {$where[2]} UNHEX('{$where[1]}')";
                }
            }
        }

        //groupBy
        if (isset($this->storage->groupby)) {
            if ($this->storage->groupby != "") {
                $this->storage->query .= " GROUP BY `{$this->storage->groupby}` ";
            }
        }

        if (is_array($this->storage->orderBy)) {
            $this->storage->query .= " ORDER BY `{$this->storage->orderBy[0]}` {$this->storage->orderBy[1]}";
        }

        //Limit
        if ($this->storage->limit != "") {
            $this->storage->query .= " LIMIT {$this->storage->offset},{$this->storage->limit}";
        }

        $this->storage->query .= ";";
        //reset the query
        $this->reset();
        return $this->storage->query;
    }

    //excute the Query
    function executeQuery($DEBUG = false) {
        $dbvalues = "";

        $this->storage->query = "";

        //Select Specfic Colomns
        if (is_array($this->storage->columns)) {
            $this->storage->query = "SELECT `" . implode("`,`", $this->storage->columns) . "` FROM";
        }

        //Select All
        if ($this->storage->columns == "*") {
            $this->storage->query = "SELECT * FROM";
        }

        //Count OR SUM OR Average
        if ($this->storage->action == "Count" || $this->storage->action == "Sum" || $this->storage->action == "Average") {
            $this->storage->query = $this->storage->columns . " FROM";
        }

        //Delete
        if ($this->storage->action == "Delete") {
            $this->storage->query = "DELETE FROM";
        }

        //Insert
        if ($this->storage->action == "Insert") {
            $this->storage->query = "INSERT";
        }

        //Multi Insert
        if ($this->storage->action == "Multi Insert") {
            $this->storage->query = "INSERT INTO";
        }

        //Update
        if ($this->storage->action == "Update") {
            $this->storage->query = "UPDATE";
        }

        //Table
        if ($this->storage->table == "") {
            return "No table Selected";
        } else {
            $this->storage->query .= " `{$this->storage->table}`";
        }

        //Multi Insert
        if ($this->storage->action == "Multi Insert") {
            $query = "";
            foreach ($this->storage->Data as $data) {
                $query .= "(";
                foreach ($data as $value) {
                    $dbvalues[] = $value;
                    $query .= "?, ";
                }
                $query = trim($query);
                $query = trim($query, ",");
                $query .= "), ";
            }
            $query = trim($query);
            $query = trim($query, ",");
            $this->storage->query .= " (`" . implode("`, `", $this->storage->Header) . "`) VALUES $query";
        }

        //Insert or Update
        if ($this->storage->action == "Insert" || $this->storage->action == "Update") {
            $this->storage->query .= " SET";
        }

        //Insert or Update Data
        if (is_array($this->storage->Data) && $this->storage->action != "Multi Insert") {
            $sql = "";
            foreach ($this->storage->Data as $key => $value) {
                $dbvalues[] = $value;
                if ($sql == "") {
                    $sql.=" `" . $key . "` = ? ";
                } else {
                    $sql.=", `" . $key . "` = ?";
                }
            }
            $this->storage->query .= $sql;
        }

        //Where
        if (is_array($this->storage->where) || is_array($this->storage->in)) {
            if (is_array($this->storage->where)) {
                $dbvalues[] = $this->storage->unhex($this->storage->where[1]);
                if ($this->storage->where[3]) {
                    $backtricks = "`";
                } else {
                    $backtricks = " ";
                }
                $this->storage->query .= " WHERE {$backtricks}{$this->storage->where[0]}{$backtricks} {$this->storage->where[2]} ?";
            }
            if (is_array($this->storage->andWhere)) {
                foreach ($this->storage->andWhere as $where) {
                    $dbvalues[] = $this->storage->unhex($where[1]);
                    if ($where[3]) {
                        $backtricks = "`";
                    } else {
                        $backtricks = " ";
                    }
                    $this->storage->query .= " AND $backtricks{$where[0]}$backtricks {$where[2]} ?";
                }
            }
            if (is_array($this->storage->orWhere)) {
                foreach ($this->storage->orWhere as $where) {
                    $dbvalues[] = $this->storage->unhex($where[1]);
                    if ($where[3]) {
                        $backtricks = "`";
                    } else {
                        $backtricks = " ";
                    }
                    $this->storage->query .= " OR $backtricks{$where[0]}$backtricks {$where[2]} ?";
                }
            }
        }

        //In
        if (is_array($this->storage->in) || is_array($this->storage->where)) {
            if (is_array($this->storage->in)) {
                $this->storage->query .= " WHERE `{$this->storage->in[0]}` IN  ({$this->storage->in[1]})";
            }

            if (is_array($this->storage->andInWhere)) {
                foreach ($this->storage->andInWhere as $where) {
                    $this->storage->query .= " AND `{$where[0]}` IN ({$where[1]})";
                }
            }
            if (is_array($this->storage->orInWhere)) {
                foreach ($this->storage->orInWhere as $where) {
                    $this->storage->query .= " OR `{$where[0]}` IN ({$where[1]})";
                }
            }
        }

        //groupBy
        if (isset($this->storage->groupby)) {
            if ($this->storage->groupby != "") {
                $this->storage->query .= " GROUP BY `{$this->storage->groupby}` ";
            }
        }

        if (is_array($this->storage->orderBy)) {
            $this->storage->query .= " ORDER BY `{$this->storage->orderBy[0]}` {$this->storage->orderBy[1]}";
        }

        //Limit
        if ($this->storage->limit != "") {
            $this->storage->query .= " LIMIT {$this->storage->offset},{$this->storage->limit}";
        }

        $this->storage->query .= ";";

        //Debug Results
        if ($DEBUG) {
            echo "<pre><b>SQL:</b> " . $this->storage->query . "<hr/><b>Values:</b>";
            print_r($dbvalues);
            echo "</pre>";
        }

        //execute the query
        $result = $this->storage->db->prepare($this->storage->query);

        //fix if no value to bind
        if ($dbvalues == "") {
            $dbvalues = array();
        }

        if ($result->execute($dbvalues)) {
            $this->storage->transactionResults[] = true;

            //result for the select
            if ($this->storage->columns == "*" || is_array($this->storage->columns)) {
                //reset the query
                $this->reset();
                return $result->fetchAll();
            }

            //result for the count, sum and average
            if ($this->storage->action == "Count" || $this->storage->action == "Sum" || $this->storage->action == "Average") {
                //reset the query
                $this->reset();
                return $result->fetchColumn();
            }
            //reset the query
            $this->reset();
            return true;
        } else {
            //reset the query
            $this->reset();
            $this->storage->transactionResults[] = false;
            return false;
        }
    }

    //Save the changes with automatic checking
    public function endTransaction() {
        if (!is_array($this->storage->transactionResults)) {
            return false;
        }
        foreach ($this->storage->transactionResults as $result) {
            if (!$result) {
                $this->roleBack();
                $this->storage->transactionResults = null;
                return false;
            }
        }
        $this->saveChanges();
        $this->storage->transactionResults = null;
        return true;
    }

    //Save the changes
    public function saveChanges() {
        $this->storage->transaction = false;
        $this->storage->db->commit();
    }

    //role back the changes before commit
    public function roleBack() {
        $this->storage->transaction = false;
        $this->storage->db->rollBack();
    }

    //reset the vars
    private function reset() {
        $this->storage->table = NULL;
        $this->storage->columns = NULL;
        $this->storage->andWhere = NULL;
        $this->storage->orWhere = NULL;
        $this->storage->where = NULL;
        $this->storage->limit = NULL;
        $this->storage->orderBy = NULL;
        $this->storage->action = NULL;
        $this->storage->Data = NULL;
        $this->storage->Header = NULL;
        $this->storage->groupby = NULL;
        $this->storage->andInWhere = NULL;
        $this->storage->orInWhere = NULL;
        $this->storage->in = NULL;
        $this->storage->offset = NULL;
    }

}
