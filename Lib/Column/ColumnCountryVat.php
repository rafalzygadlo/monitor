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


class ColumnCountryVat extends Column
{
    
    public function __construct($name, $fieldname, $country_iso_2, $visible = true)
    {
        $this->Name = $name;
        $this->FieldName = $fieldname; 
        $this->CountryISO2 = $country_iso_2;
        $this->Visible = $visible;
    }

    
    public function Render($item)
    {
        $name = $this->FieldName;

        switch($this->CountryISO2)
        {
            case "RO":	return 9;
            case "GB":  return 20;
            case "DE":  return 7;
            default:    return $item->$name;
        }
 
    }

}

