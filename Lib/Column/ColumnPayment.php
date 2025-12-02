<?php

/**
 * ColumnPayment
 * 
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;

define("PRZELEW","platnosc_przelew");
define("STANDARD","platnosc_standard");
define("PAYPAL","platnosc_paypal");
define("PRZELEW24","platnosc_przelewy24");
define("PAYU","platnosc_payu");

class ColumnPayment extends Column
{

    public function Render($item)
    {
        $name = $this->FieldName;
        
        switch($item->$name)
        {
            case STANDARD:      return "Pobranie";
            case PRZELEW:       return "Przelew bankowy";
            case PAYPAL:        return "PayPal";
            case PRZELEW24:     return "Przelew24";
            case PAYU:          return "PayU";
        }
        
    }

}

