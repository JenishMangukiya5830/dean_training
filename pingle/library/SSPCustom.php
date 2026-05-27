<?php

namespace library;

class SSPCustom extends SSP {

    /**
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $sql_details SQL connection details - see sql_connect()
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @param  string $whereCustom Custom (additional) WHERE clause
     *  @return array          Server-side processing response array
     */
    static function simpleCustom($request, $db, $table, $primaryKey, $columns, $whereCustom = '') {
        $bindings = array();
        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        if ($whereCustom) {
            if ($where) {
                $where .= ' AND ' . $whereCustom;
            } else {
                $where .= 'WHERE ' . $whereCustom;
            }
        }

        // Main query to actually get the data
        $data = self::sql_exec($db, $bindings, "SELECT SQL_CALC_FOUND_ROWS `" . implode("`, `", self::pluck($columns, 'db')) . "`
             FROM `$table`
             $where 
             $order
             $limit"
        );
        //print_r($where);
        // Data set length after filtering
        $resFilterLength = self::sql_exec($db, "SELECT FOUND_ROWS()"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($db, "SELECT COUNT(`{$primaryKey}`)
             FROM   `$table`
             WHERE  " . $whereCustom
        );
        if (isset($resTotalLength[0])) {
            $recordsTotal = $resTotalLength[0][0];
        } else {
            $recordsTotal = 0;
        }
        /*
         * Output
         */
        return array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columns, $data)
        );
    }

}
