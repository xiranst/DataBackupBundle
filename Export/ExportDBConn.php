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
class ExportDBConn
{
    public $host;
    public $username;
    public $password;
    public $name;

    protected $connection;

    function __construct($options) {
        $this->host = $options['host'];
        if (empty($this->host)) {
            $this->host = '127.0.0.1';
        }
        $this->username = $options['username'];
        $this->password = $options['password'];
        $this->name = $options['db_name'];
    }

    static function create($options) {
        if (class_exists('mysqli')) {
            $class_name = "Dianzibuy\Bundle\DatabaseBundle\Export\ExportDBConnMysqli";
        } else {
            $class_name = "Dianzibuy\Bundle\DatabaseBundle\Export\ExportDBConnMysql";
        }

        return new $class_name($options);
    }
}