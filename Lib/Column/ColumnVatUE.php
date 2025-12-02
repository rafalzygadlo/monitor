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


class ColumnVatUE extends Column
{

    public function __construct($name, $fieldname, $values, $visible = true)
    {
        $this->Name = $name;
        $this->Values = $values;
        $this->FieldName = $fieldname;
        $this->Visible = $visible;
    }

    public function Render($item)
    {
        $name = $this->FieldName;
        
        //print 'VATUE' . $this->Values->$name;
        if($this->Values->$name == "u")
            return '1';
        else
            return '0';
        
    }

}

