<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12/28/17
 * Time: 1:33 PM
 */

namespace Xiranst\Bundle\DataBackupBundle\Export;

/**
 * Plain text implementation. Uses standard file functions in PHP.
 */
class ExportFilePlaintext extends ExportFile {
    function open() {
        return fopen($this->file_location, 'w');
    }
    function write($string) {
        return fwrite($this->fh, $string);
    }
    function end() {
        return fclose($this->fh);
    }
}