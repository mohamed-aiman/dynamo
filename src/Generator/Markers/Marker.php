<?php

namespace Generator\Markers;

class Marker
{
	protected $content;

	public function __construct($content = null)
	{
		$this->content = ($content) ? $content : null;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function bindValues($withNeedleAndValue = [], &$haystack)
	{
		foreach ($withNeedleAndValue as $needle => $value) {
			$x = str_replace($needle, $value, $haystack);
		}
	}

	public function between($start, $end, $content = null)
	{
		$string = ($content) ? $content : $this->content;
	    $ini = strpos($string, $start);
	    // if ($ini == 0) return '';
	    $ini += strlen($start);
	    $len = strpos($string, $end, $ini) - $ini;
	    return substr($string, $ini, $len);
	}

	/**
	*
	*  works with strings having quotes
	**/
	public function getInbetween($start, $end, $string) {

        try{
            $startsAt = strpos($string, $start) + strlen($start);
            $endsAt = strpos($string, $end, $startsAt);
            $result = substr($string, $startsAt, $endsAt - $startsAt);
        } catch (\Exception $e){
            $result = null;
        }
        return $result;
    }

	public function betweenAll($startTag, $endTag = null, $content = null)
    {
		$string = ($content) ? : $this->content;
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

	public function inbetweenFirstAndEOL($firstWord, $content = null)
	{
		$content = ($content) ? : $this->content;

	}

	public function allWhereInbetweenFirstAndSecond($firstWord, $secondWord, $content = null)
	{
		$content = ($content) ? : $this->content;

	}
}