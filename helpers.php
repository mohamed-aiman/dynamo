<?php

/*
 * This file contains a part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('dump')) {
    /**
     * @author Nicolas Grekas <p@tchwork.com>
     */
    function dump($var, ...$moreVars)
    {
        VarDumper::dump($var);

        foreach ($moreVars as $v) {
            VarDumper::dump($v);
        }

        if (1 < func_num_args()) {
            return func_get_args();
        }

        return $var;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars)
    {
        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        die(1);
    }
}

if (!function_exists('vd')) {
    function vd(...$vars)
    {
        foreach ($vars as $v) {
            var_dump($v);
        }

        die(1);
    }
}

/**
 * usage example:
 * get_string_between($this->spaceReplaced($content), "Schema::create('", "',function(Blueprint");
 * returns the table name
 */
if (!function_exists('get_string_between')) {
    function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}

/**
 * usage example:
 * $data = $this->getInbetweenAll($this->spaceReplaced($content), '$table->', ");");
 * returns array
 * array:8 [
  0 => "increments('id'"
  1 => "string('name',191)->nullable("
  2 => "string('phone',191)->nullable("
  3 => "enum('type',['shop','individual'])->nullable("
  4 => "integer('island_id',10)->nullable("
  5 => "dateTime('created_at')->nullable("
  6 => "dateTime('updated_at')->nullable("
  7 => "timestamps("
]
 */
if (!function_exists('get_string_between_all')) {
    function get_string_between_all($string, $startTag, $endTag = null)
    {
        $endTag = ($endTag == null) ? $startTag : $endTag;
        $delimiter = '#';
        $regex = $delimiter . preg_quote($startTag, $delimiter) 
                            . '(.*?)' 
                            . preg_quote($endTag, $delimiter) 
                            . $delimiter 
                            . 's';
        preg_match_all($regex,$string,$matches);

        return $matches[1];
    }
}

if (!function_exists('get_string_upto')) {
    function get_string_upto($string, $delimiter)
    {
        $arr = explode($delimiter, $string, 2);
        return $arr[0];
    }
}