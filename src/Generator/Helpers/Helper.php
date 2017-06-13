<?php

namespace Generator\Helpers;

trait Helper{

	public function clean($value)
    {
        $value = preg_replace('/\s+/', '', $value);
        $value = str_replace('`','',$value);
        $value = str_replace('(','',$value);
        $value = str_replace(')','',$value);
        $value = str_replace(',','',$value);
        return $this->myTrim($value);
    } 

    public function myTrim($string = null)
    {
        $string = trim($string, '"');
        $trimmed = trim($string, "'");
        return $trimmed;
    }


	public function spaceReplaced($string)
	{
		return str_replace(' ', '', $string);
	}

	/**
	*
	* helper to replace original array
	**/
	public function setDynamicValue($new, &$original)
	{
		$original = $new;
	}

}