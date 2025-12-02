<?php


/**
 * Tools
 * 
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */


namespace Lib;

class Tools
{

    public function AsString()
    {
        return get_class($this);
    }    
    
    public function GetDiskTotalSpace($directory)
    {
        return disk_total_space($directory)/1024/1024/1024;
    }
    
    public function GetDiskFreeSpace($directory = '/home/qotsa2')
    {
        return disk_free_space($directory);
    }

    public static function RandomString($len)
    {
        $string_table = "qazwsxedcrfvtgbyhnujmikolpQAZWSXEDCRFVTGBHNUJMIKOLP1234567890";
        $buffer = "";
        for ($i = 0; $i < $len; $i++)
            $buffer = $buffer . $string_table[rand(0, strlen($string_table) - 1)];

        return $buffer;
    }
    
    
    public static function DirectoryCreate($path)
    {
        if(!Tools::DirectoryExists($path))
            @mkdir($path, 0777, true);
    }
    
    public static function DirectoryExists($path)
    {
        if (!file_exists($path))
        {
            return false;
        }else{
     
        return true;
        }
    } 
 
  
    
}