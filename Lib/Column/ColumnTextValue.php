<?php

/**
 * ColumnText
 * 
 * @category   Lib
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;


class ColumnTextValue extends Column
{

    
    public function __construct($name, $value, $visible = true)
    {
        $this->Name = $name;
        $this->Value = $value;
        $this->Visible = $visible;
    }

    public function Render($item)
    {
        return $this->Value;
    }

}

