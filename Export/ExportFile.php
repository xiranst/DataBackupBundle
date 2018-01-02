<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12/28/17
 * Time: 1:33 PM
 */

namespace Xiranst\Bundle\DataBackupBundle\Export;

/**
 * Abstract dump file: provides common interface for writing
 * data to dump files.
 */
abstract class ExportFile
{
    /**
     * File Handle
     */
    protected $fh;

    /**
     * Location of the dump file on the disk
     */
    protected $file_location;

    abstract function write($string);
    abstract function end();

    static function create($filename) {
        if (self::is_gzip($filename)) {
            return new ExportFileGzip($filename);
        }
        return new ExportFilePlaintext($filename);
    }
    function __construct($file) {
        $this->file_location = $file;
        $this->fh = $this->open();

        if (!$this->fh) {
            throw new ExportException("Couldn't create gz file");
        }
    }

    public static function is_gzip($filename) {
        return preg_match('~gz$~i', $filename);
    }
}