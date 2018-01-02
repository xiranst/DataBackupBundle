<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12/28/17
 * Time: 2:02 PM
 */

namespace Xiranst\Bundle\DataBackupBundle\Import;

class Import
{
    private $connection;

    public function __construct($host, $username, $password, $dbname)
    {
        $this->connection = mysqli_connect($host, $username, $password, $dbname);
        if (!$this->connection)
            die('Could not connect: ' . mysqli_error());
    }

    public function import($datafile)
    {
        $array = explode(".", $datafile);
        $extension = end($array);
        if($extension != 'sql')
            return '系统提示:需要导入的SQL文件后缀不是sql文件,请检查后再操作';
        $file_data = file($datafile);
        $output = '';
        $count = 0;
        foreach($file_data as $row)
        {
            $start_character = substr(trim($row), 0, 2);
            if($start_character != '--' || $start_character != '/*' || $start_character != '//' || $row != '')
            {
                $output = $output . $row;
                $end_character = substr(trim($row), -1, 1);
                if($end_character == ';')
                {
                    if(!mysqli_query($this->connection, $output))
                    {
                        $count++;
                    }
                    $output = '';
                }
            }
        }
        if($count > 0)
        {
            $message = '系统提示:导入的数据有误';
        }
        else
        {
            $message = '系统提示:数据导入成功';
        }
        return $message;
    }
}