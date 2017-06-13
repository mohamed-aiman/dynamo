<?php 
namespace Generator\Helpers;

trait GeneralHelper
{
    public function collectionAsArray($data)
    {
        return ($data instanceof Collection)
            ? $data->toArray()
            : $data;
    }

    public function contains($haystack, $needle)
    {
        if(strpos($haystack, $needle) == false) {
            return false;
        } else {
            return true;
        }
    }

    public function nearestMatchFromArray($contents, $ends)
    {
        if(is_array($ends)) {
            foreach ($ends as $end) {
                $content[] = $this->nearestMatchFromArray($contents, $end);
            }         
        } else {
            $content[] = strstr($contents, $ends, true);
        }
        
        if(!min($content)) {
            return $content;
        }
        return min($content);
    }

    public function getFilesNames($path = null)
    {
        $fileNames = [];
        $collection = $this->filesystem->allFiles($path);
        foreach($collection as $file) {
                $fileNames[] = $file->getRealPath();
        }
        return $fileNames;
    }

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

    public function myArrayTrim($mixed = null)
    {
        if($mixed == null) {
            return null;
        }

        if($this->isIterable($mixed)) {
            foreach($mixed as $element) {
                $trimmed[] = $this->myArrayTrim($element);
            }
        } else {
            $trimmed[] = $this->myTrim($mixed);
        }

        return $trimmed;
    }

    public function isIterable($var)
    {
        return $var !== null 
            && (is_array($var) 
                || $var instanceof Traversable 
                || $var instanceof Iterator 
                || $var instanceof IteratorAggregate
                );
    }

    public function getInbetween($string, $start, $end) {

        try{
            $startsAt = strpos($string, $start) + strlen($start);
            $endsAt = strpos($string, $end, $startsAt);
            $result = substr($string, $startsAt, $endsAt - $startsAt);
        } catch (\Exception $e){
            $result = null;
        }
        return $result;
    }

    public function getInbetweenAll($string, $startTag, $endTag = null)
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


    public function multiToSingleDimension($ary)  
    {
        $lst = array();
        foreach( array_keys($ary) as $k ) {
            $v = $ary[$k];
            if (is_scalar($v)) {
                $lst[] = $v;
            } elseif (is_array($v)) {
                $lst = array_merge($lst, $this->multiToSingleDimension($v));
            }
        }
        return $lst;
    }
}