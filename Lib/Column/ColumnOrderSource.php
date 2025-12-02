<?php

/**
 * ColumnOrderTYpe
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;

define("GUEST",1);
define("REGISTERED",2);
define("BASELINKER",3);

 //(PAR, FVS, FVW, WDT, EXP)

class ColumnOrderSource extends Column
{

    public function Render($item)
    {
        $name = $this->FieldName;
        
        switch($item->$name)
        {
            case GUEST:
            case REGISTERED:
                return "A";
            case BASELINKER:
                return "B";
        }
        
    }

}

