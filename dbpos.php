<?php

/**
 *  Copyright (c) 2018. Nicolas Choukroun.
 *  Copyright (c) 2018. The PHPSnipe Developers.
 *  This program is free software; you can redistribute it and/or modify it
 *  under the terms of the Attribution 4.0 International License as published by the
 *  Creative Commons Corporation; either version 2 of the License, or (at your option)
 *  any later version.  See COPYING for more details.
 *
 * **************************************************************************** */
class DatabasePG {

    public $host = DB_HOST_PG;        // Hostname of our MySQL server.
    public $dbname = DB_DATABASE_PG;         // Logical database name on that server.
    public $user = DB_USERNAME_PG;             // User and Password for login.
    public $password = DB_PASSWORD_PG;
    public $port = DB_PORT_PG;
    public $rs = array();
    private $db;       //The db handle
    public $num_rows; //Number of rows
    public $last_id;  //Last insert id
    public $aff_rows; //Affected rows
    private $result;

    public function __construct() {
        $con = "host=" . $this->host . " port=" . $this->port . " dbname=" . $this->dbname . " user=" . $this->user . " password=" . $this->password;
        //echo $con."<br>";
        $this->db = pg_connect($con);
        if (!$this->db)
            die("Impossible to connecto to PG database.");
    }

    public function close() {
        pg_close($this->db);
    }

    // Returns one row as object
    public function single() {
        if ($this->result == 0)
            die("PG Database not initialized");

        $row = pg_fetch_object($this->result);
        $rs = $row;
        if (pg_last_error())
            exit(pg_last_error());
        return $row;
    }

    // Returns number of rows
    public function nbr() {
        return $aff_rows;
    }

    // For SELECT
    // Returns one row as object
    public function getRow($sql) {
        $this->result = pg_query($this->db, $sql);
        $row = pg_fetch_object($this->result);
        $rs = $row;
        if (pg_last_error())
            exit(pg_last_error());
        return $row;
    }

    // For SELECT
    // Returns an array of row objects
    // Gets number of rows
    public function getRows($sql) {
        $this->result = pg_query($this->db, $sql);
        if (pg_last_error())
            exit(pg_last_error());
        $this->num_rows = pg_num_rows($this->result);
        $rows = array();
        while ($item = pg_fetch_object($this->result)) {
            $rows[] = $item;
        }
        return $rows;
    }

    public function next() {
        if ($this->result == 0)
            die("DBPG next(): PG Database not initialized.");
        $rows = array();
        $item = pg_fetch_array($this->result, NULL, PGSQL_ASSOC);
        $rows = $item;
        $this->rs = $item;

        return $rows;
    }

    // For SELECT
    // Returns one single column value as a string
    public function getCol($sql) {
        $this->result = pg_query($this->db, $sql);
        $col = pg_fetch_result($this->result, 0);
        if (pg_last_error())
            exit(pg_last_error());
        return $col;
    }

    // For SELECT
    // Returns array of all values in one column
    public function getColValues($sql) {
        $result = pg_query($this->db, $sql);
        $arr = pg_fetch_all_columns($result);
        if (pg_last_error())
            exit(pg_last_error());
        return $arr;
    }

    // For INSERT
    // Returns last insert $id
    public function insert($sql, $id = 'id') {
        $sql = rtrim($sql, ';');
        $sql .= ' RETURNING ' . $id;
        $this->result = pg_query($this->db, $sql);
        if (pg_last_error())
            exit(pg_last_error());
        $this->last_id = pg_fetch_result($this->result, 0);
        return $this->last_id;
    }

    // For UPDATE, DELETE and CREATE TABLE
    // Returns number of affected rows
    public function query($sql) {
        $this->result = pg_query($this->db, $sql);

        if (pg_last_error() || !$this->result)
            exit("DBPG: Query: " . pg_last_error() . "=>" . $sql);
        $this->aff_rows = pg_affected_rows($this->result);
        return $this->aff_rows;
    }

}

?>
