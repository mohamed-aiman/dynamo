<?php

namespace Generator\Migrations;


class FetchTableContent
{
    public static function getTableContents($content, $tableName = null)
    {
        $contents = [];
        // self::iterateOverEachLineInString($content, $tableName);
        return $contents;
    }

    // protected function iterateOverEachLineInString($subject, &$contains)
    // {
    //     $separator = "\r\n";
    //     $line = strtok($subject, $separator);

    //     while ($line !== false) {
    //         if (self::fetchTableQuery($line)) {
    //             $contains[] = self::cleanLine($line);
    //         }
    //         $line = strtok( $separator );
    //     }
    // }

    // protected function fetchTableQuery($line, $type = 'CREATE')
    // {
    //     if (!in_array($type, [
    //         'CREATE',
    //         'ALTER'
    //     ])) {
    //         // throw new Exception("Currently fetching for tables allowed only for CREATE, ALTER");
    //         throw new Exception("Currently fetching for tables allowed only for CREATE");
    //     }

    //     if (strpos($line, "$type TABLE ") !== false) {
    //         return true;
    //     }

    //     return false;
    // }

    // protected function cleanLine($line)
    // {
    //     // $str = strstr($line, 'ALTER TABLE');
    //     $str = strstr($line, 'CREATE TABLE');
    //     $str = strstr($str, 'CREATE TABLE IF NOT EXISTS');
    //     $str = strstr($str, '`.`');
    //     $str = str_replace('CREATE TABLE IF NOT EXISTS', '', $str);
    //     $str = str_replace('CREATE TABLE', '', $str);
    //     $str = str_replace('`.`', '', $str);
    //     // $str = str_replace('ALTER TABLE', '', $str);
    //     $str = strstr($str, '`', true);
    //     return $str;
    // }

}