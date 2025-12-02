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

use Lib\FileLog;

class ColumnPaymentNew extends Column
{

    public function Render($item)
    {

        $name = $this->FieldName;

        switch(trim($item->$name))
        {

				case "Ozon Payments":
			return "Ozon Payments";
			
				case "gotówka":
				case "Gotówka":
			return "Gotówka";

				case "Cash on delivery":
				case "Payment at the delivery":
				case "platność przy odbiorze":
				case "Płatność pfzy odbiorze":
				case "Gls pobranie":
				case "Płatność na pobraniem":
				case "platnosc za pobraniem":
				case "za pobraniem":
				case "Za pobraniem":
				case "Płatność za pobraniem":
				case "płatność za pobraniem":
				case "COD":
				case "Płatność przy odbiorze":
				case "płatność przy odbiorze":
				case "pobranie":
				case "Pobranie":
				case "POBRANIE":
            return "Pobranie";

				case "Zwykły przelew":
				case "Płatność przelewem":
				case "płatność przelewem":
				case "przelew":
				case "Kreditkarte akzeptiert":
				case "Wire Transfer":
				case "Überweisung":
				case "Przelew bankowy":
				case "przelew bankowy":
				case "Online card payment":
				case "Przelew bankowy PLN / EUR / GBP / USD":
				case "Przelew":
				case "przelewy24":
			return "Przelew";

				case "Przelewy24 (Debit and Credit Card / online bank transfer / BLIK)":
				case "Przelewy24 (przelew online / karta / BLIK)":
				case "Przelewy24 (banki / karta / BLIK)":
				case "Przelewy24":
				case "Przelewy 24":
			return "Przelewy24";

				case	"PayU":
				case	"Raty PayU":
				case	"PayU - szybka płatność kartą":
				case	"PayU - szybka platność przelewem":
				case	"payu":
			return "PayU";


				case	"tpay.com":
				case	"Tpay.com - Online payment / CARD / BLIK":
				case	"Płatności online Tpay - przelew bankowy / KARTA / BLIK":
				case	"Tpay.com - płatności online / KARTA / BLIK":
				case	"Tpay.com - płatności online":
				case	"Tpay.com - Online payment":
				case	"tpay.com - płatności online":
			return "Tpay";


				case	"PayPal / Revolut":
				case	"PayPal / Revolut / Debit and Credit Card":
				case	"PayPal / Revolut / Karta kredytowa i debetowa":
				case	"PayPal":
				case 	"paypal":
				case 	"Paypal":
			return "PayPal";

			case "voucher":
				return "voucher";

			case "Płatność przy odbiorze (Kurier GLS)":
				 return "Płatność przy odbiorze (Kurier GLS)"; 

			case "Płatność przy odbiorze (Kurier DPD)":
				 return "Płatność przy odbiorze (Kurier DPD)"; 
			
			case "Płatność przy odbiorze (Pocztex 48)":
				return "Płatność przy odbiorze (Pocztex 48)";

			case "Płatność przy odbiorze (Paczkomaty Inpost)":
				return "Płatność przy odbiorze (Paczkomaty Inpost)";

			case "Płatność przy odbiorze (DHL Parcel)":
			case "Payment at the delivery (DHL Parcel)":
				return "Płatność przy odbiorze (DHL Parcel)";

			case "Płatność przy odbiorze (e-przesyłka/orlen/żabka/freshmarket)":
				return "Płatność przy odbiorze (e-przesyłka/orlen/żabka/freshmarket)";
				
			case "Płatność przy odbiorze (Paczka w ruchu)":
				return "Płatność przy odbiorze (Paczka w ruchu)";
			
			case "Płatność przy odbiorze (Kurier SAMEDAY)":
			case "Cash on delivery (SAMEDAY courier)":
				return "Płatność przy odbiorze (Kurier SAMEDAY)";

		}
		
		FileLog::Write("orderCtrl: order_id: ".$item->orders_id." payment not found:".$item->$name);
    	
		return $item->$name;

    }

}

