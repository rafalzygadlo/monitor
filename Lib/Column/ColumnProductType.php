<?php

/**
 * ColumnText
 * 
 * @category   Lib
 * @package    mass-symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;


class ColumnProductType extends Column
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
        
        foreach($this->Values as $value)
        {
            //print $item->$name;
            if($value->GetName() == $item->$name)
                return $value->GetId();
        }
    
    }

}

