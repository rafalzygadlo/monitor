<?php

/**
 * File
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib\File;

use Lib\File\File;

class FileItemWriter extends File
{
    
   function __construct($file, $folder, $items, $columns, $separator)
   {
         parent::__construct($file, $folder, $separator);
         $this->Items = $items;
         $this->Columns = $columns;

         // separator na 0 - bedzie na końcu
         // separator na 1 - nie bedzie na końcu
         $this->Separator = SEPARATOR_AT_THE_END;
   }

   private function Items()
   {
	
   	  $count_items = count($this->Items);
   	  $counter = 1;
      foreach ($this->Items as $item)
      {
         $this->Separator = SEPARATOR_AT_THE_END;
         $count = count($this->Columns);

         foreach($this->Columns as $column)
         {

            if($column->Visible)
            {
               $this->WriteItem($column->Render($item));
            }

            if ($this->Separator < $count)
               $this->WriteSeparator();

            $this->Separator++;
         }

         if($count_items > $counter)
         	$this->WriteEOL();

		$counter++;
      }
    }
    
    public function Run()
    {
         //delete old file
         $this->Delete();
         $this->Items();
         //$this->Rename();
    }
    
    
}

