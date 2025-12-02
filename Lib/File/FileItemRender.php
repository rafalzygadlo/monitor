<?php

/**
 * FileItemRender
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib\File;


class FileItemRender
{
    
    function __construct($items, $columns)
    {
        $this->Items = $items;
        $this->Columns = $columns;
    }
    
    private function Items()
    {
        
        foreach ($this->Items as $item)
        {
            foreach($this->Columns as $column)
            {
        
                if($column->Visible)
                {
                    print $column->Render($item);
                }
                
                print ';';
            
            }
            
            print '<br>';
        }
    }
    
    public function Run()
    {
        $this->Items();
    }
    
    
}

