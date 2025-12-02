<?php

/**
 * FileReader
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib\File;

use Lib\File\File;

class FileReader 
{
   
   public $Records = array();
       
   function __construct($file, $column_count)
   {
      $this->Records = array();
      $this->File = $file;
      $this->ColumnCount = $column_count;

   }
    
    private function Read()
    {
         $file = fopen($this->File,"r");

         if($file)
         {
            while(!feof($file))
            {
               $line = fgets($file);
               
               if(!empty($line))
               {
                  $parts = explode(FILE_DATA_SEPARATOR, $line);
                  $columns = 0;
               
                  //policz kolumny
                  $columns = count($parts);
                           
                  if($columns == $this->ColumnCount)
                  {
                     array_push($this->Records,$parts);
                  }
                  else
                  {
                     printf("number of columns %d %d",$columns,$this->ColumnCount);
		    		 return false;
                  }
               }
            }
         
            fclose($file);
	    	return true;
         }
    }
    
    public function Run()
    {
         return $this->Read();
         //$this->Header();
         //$this->Items();
    }
    
    
}

