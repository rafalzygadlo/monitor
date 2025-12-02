<?php

/**
 * ColumnText
 * 
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;

define("PARAGON",0);
define("FAKTURA",1);

 //(PAR, FVS, FVW, WDT, EXP)

class ColumnDoc extends Column
{

    public function Render($item)
    {
        $name = $this->FieldName;
        
        switch($item->$name)
        {
            case PARAGON:   return "FVS";
            case FAKTURA:   return "FVS";
        }
        
    }

}

