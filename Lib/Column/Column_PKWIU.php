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


class Column_PKWIU extends Column
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
        if($this->Values == NULL)
        {
            return NULL;
        }

    
        @list($value) = explode(".", $this->Values->$name, 2);
            
        return $value;
    }

}

