<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12/28/17
 * Time: 1:33 PM
 */

namespace Xiranst\Bundle\DataBackupBundle\Export;

/**
 * Gzip implementation. Uses gz* functions.
 */
class ExportFileGzip extends ExportFile {
    function open() {
        return gzopen($this->file_location, 'wb9');
    }
    function write($string) {
        return gzwrite($this->fh, $string);
    }
    function end() {
        return gzclose($this->fh);
    }
}