<?php

use library\pingle\VirtualTable;

//virtual table object
$virtualtable = new VirtualTable();

//list of the tables
$tables = array('table1', 'table2', 'table3', 'table4');

function configure(Array $tables, $currentPage, \PDO $database, $limit, Array $post, $countUrl, $dataUrl, $secret) {
    
}
