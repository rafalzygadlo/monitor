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


class Column_KOSZT_WYSYLKI extends Column
{
    
    public function __construct($name, $fieldname, $shipping, $payment, $currency_value, $visible = true)
    {
        $this->Name = $name;
        $this->Shipping = $shipping;
        $this->Payment = $payment;
        $this->CurrencyValue = $currency_value;
        $this->FieldName = $fieldname; 
        $this->Visible = $visible;
    }

    public function Render($item)
    {
    
        $name = $this->FieldName;
        $value = ( $this->Shipping + $this->Payment );
	            
        return $value;
    
    }
    
    /*
    public function Render($item)
    {
        
        $name = $this->FieldName;

        @list($option,$value) = explode(":", $this->Shipping->$name, 2);
        $value = ($value + $this->Payment->$name) * $this->CurrencyValue;

        return $value;
        
    }
    */

}

