<?php

/**
 * FileRenderer
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib\File;


class FileRender
{
    
    function __construct($header, $items)
    {
         $this->Header = $header;
         $this->Items = $items;    
    }
    
    private function Header()
    {
        foreach($this->Header->Columns as $column)
        {
            if($column->Visible)
            {
                print $column->Render($this->Header);    
            }
            
            print ';';
        }
        
        print '<br>';    
    }
    
    private function Items()
    {
        
        foreach ($this->Items as $item)
        {
            foreach($item->Columns as $column)
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
        //$this->Header();
        $this->Items();
        print '<br>';
    }
    
    
}

