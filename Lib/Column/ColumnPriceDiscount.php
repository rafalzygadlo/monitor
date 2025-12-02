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


class ColumnPriceDiscount extends Column
{
    
    public function __construct($name, $fieldname, $discount, $visible = true)
    {
        $this->Name = $name;
        $this->FieldName = $fieldname; 
        $this->Discount = $discount;
        $this->Visible = $visible;
    }

    
    public function Render($item)
    {
        $name = $this->FieldName;
        $total = $item->$name - ($item->$name * $this->Discount);
        
        return sprintf("%.2f",round($total,2));
    }

}

