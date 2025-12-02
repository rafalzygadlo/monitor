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

class FileWriter extends File
{

   function __construct($file, $folder, $header, $items, $separator)
   {
         parent::__construct($file, $folder, $separator);
         $this->Header = $header;
         $this->Items = $items;

   }

    private function Header()
    {
         $this->Separator = SEPARATOR_AT_THE_END;
         $count = count($this->Header->Columns);

         foreach($this->Header->Columns as $column)
         {
            if($column->Visible)
            {
               $this->WriteItem($column->Render($this->Header));
            }

            if ($this->Separator < $count)
               $this->WriteSeparator();

            $this->Separator++;  
         }
        
         $this->WriteEOL();
    }
    
   private function Items()
   {
         $counter = 1; // dla pierwszej kolumny lp
         $count_items = count($this->Items);
         foreach ($this->Items as $item)
         {
            
            $this->Separator = SEPARATOR_AT_THE_END;
            $count = count($item->Columns);
         
            $this->WriteItem($counter);
            $this->WriteSeparator();
            
            foreach($item->Columns as $column)
            {
        
               if($column->Visible)
               {
                   $this->WriteItem($column->Render($item));
               }
               
               if ($this->Separator < $count)
                  $this->WriteSeparator();
                  
               $this->Separator++;
            }

            if($counter < $count_items)
            	$this->WriteEOL();

            $counter++;
        }
    }
    
    
   public function Run()
   {
      // usuwamy stary plik
      $this->Delete();
      
      $this->Header();
      $this->Items();
      //$this->Rename();
      
   }
    
    
}

