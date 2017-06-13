<?php
namespace Generator\ClassGenerator\Helpers;

trait HelperTrait
{
    /**
     * [valueKeyArrayToString description]
     * @param  [type] $array             [the array with keys and $values, example = ["param1"=>"array","param2"=>"","param3"=>"null"]]
     * @param  string $seperator         [comma, to seperate in between elements]
     * @param  string $valueKeyDelimeter [=, to seperate in between $key and $value]
     * @return [string]                    [example = param1=array,param2,param3=null ]
     */
    public function valueKeyArrayToString($array, $seperator = ',', $valueKeyDelimeter = '=')
    {
        return implode($seperator, array_map(
            function ($v, $k) use ($valueKeyDelimeter) {
                if($v) {
                    return $k. $valueKeyDelimeter .$v;
                } else {
                    return $k;
                }
            }, 
            $array, 
            array_keys($array)
        ));
    }
}