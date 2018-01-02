<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12/28/17
 * Time: 1:33 PM
 */

namespace Xiranst\Bundle\DataBackupBundle\Export;

/**
 * Main facade
 */
class ExportDBConnMysql extends ExportDBConn
{
    function connect() {
        $this->connection = @mysql_connect($this->host, $this->username, $this->password);
        if (!$this->connection) {
            throw new ExportException("Couldn't connect to the database: " . mysql_error());
        }

        $select_db_res = mysql_select_db($this->name, $this->connection);
        if (!$select_db_res) {
            throw new ExportException("Couldn't select database: " . mysql_error($this->connection));
        }

        return true;
    }

    function query($q) {
        if (!$this->connection) {
            $this->connect();
        }
        $res = mysql_query($q);
        if (!$res) {
            throw new ExportException("SQL error: " . mysql_error($this->connection));
        }
        return $res;
    }

    function fetch_numeric($query) {
        return $this->fetch($query, MYSQL_NUM);
    }

    function fetch($query, $result_type=MYSQL_ASSOC) {
        $result = $this->query($query, $this->connection);
        $return = array();
        while ( $row = mysql_fetch_array($result, $result_type) ) {
            $return[] = $row;
        }
        return $return;
    }

    function escape($value) {
        if (is_null($value)) {
            return "NULL";
        }
        return "'" . mysql_real_escape_string($value) . "'";
    }

    function escape_like($search) {
        return str_replace(array('_', '%'), array('\_', '\%'), $search);
    }

    function get_var($sql) {
        $result = $this->query($sql);
        $row = mysql_fetch_array($result);
        return $row[0];
    }

    function fetch_row($data) {
        return mysql_fetch_assoc($data);
    }
}