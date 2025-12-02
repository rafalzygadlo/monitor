<?php
//33420
/**
 * orderModel
 * 
 * @category   Model
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */

 namespace Model\Order;
 
 use Lib\Model;
 use Lib\Column\ColumnText;
 use Lib\Column\ColumnTextValue;
 use Lib\Column\ColumnDate;
 use Lib\Column\ColumnVatUE;
 use Lib\Column\Column_PKWIU;
 use Lib\Column\Column_KOSZT_WYSYLKI;
 use Lib\Column\ColumnCurrency;
 use Lib\Column\ColumnPayment;
 use Lib\Column\ColumnPaymentNew;
 use Lib\Column\ColumnOrderSource;
 use Lib\Column\ColumnCountryISO2;
 use Lib\FileLog;
 use Lib\Soap\MySoapClient;
 use Model\CountryModel;
 use Model\MaxkodPaymentMethodModel;
 use PDO;
 
class OrderModel extends BaseOrderModel
{
    
    //czy nip zarejestrowany w UE
    public $RegisteredVat;

    function __construct()
    {    
        parent::__construct(); 
    }
    
    // ustaw dane modelu kolumny itp
    public function Init()
    {
        $this->RegisteredVat = false;
        $this->IsRegisteredVat();
        $this->SetColumnsValues();
    }

    private function IsRegisteredVat()
    {
          //czy kraj w ue
        if($this->CountryInUE())
        {
            $iso2 = $this->GetISO2($this->orders_id);
            //$vat = preg_replace('/[^0-9]/','', $this->customers_nip);
            $trans = array($iso2 => "", "-" => "");
            $vat = strtr($this->customers_nip, $trans);
            $vat = trim($vat);
            
            if(!empty($vat))
            {
                $soap = new MySoapClient();
                if($soap->isValidVat($iso2, $vat))
                {
                    $this->RegisteredVat = true;
                    FileLog::Write("InVat:".$this->orders_id."-(".$iso2.")(".$vat.")(".$this->customers_nip.")");
                }
                else
                {
                    FileLog::Write("NotInVat:".$this->orders_id."-(".$iso2.")(".$vat.")(".$this->customers_nip.")");
                }
  
            }
              
        }
          
    }

   
    private function SetColumnsValues()
    {
	
        $this->Columns = array
        (   
          /*01*/  new ColumnText('ORDER_ID','orders_id'),                         // 100200272
          /*02*/  new ColumnDate('ORDER_DATE','date_purchased'),                  // 21.12.2017
          /*03*/  new ColumnDate('PAYMENT_DATE','date_purchased'),                // 21.12.2017
          /*04*/  new ColumnTextValue('PAYMENT_TYPE','BANK'),                     // BANK
          /*05*/  new ColumnPaymentNew('PAYMENT_FORM','payment_method'),    	  // przelew int.     - forma płatnosci, z tabeli modules_payment 2021-01-14
          /*06*/  new Column_PKWIU('SHIPPING_TYPE','wartosc',$this->PKWIU()),       // 10014642         - sposób dostawy z listy dostaw
          /*07*/  new Column_KOSZT_WYSYLKI('SHIPPING_PRICE','wartosc',$this->KOSZT_WYSYLKI(),$this->KOSZT_PLATNOSCI(),$this->currency_value),      // 10,00            - cena dostawy brutto

          /*08*/  new ColumnText('BILL_KH_CODE','customers_id'),                                  // M_BS_INC_221933  - kod kh
          /*09*/  new ColumnTextValue('BILL_KH_NAME',$this->BillingName()),                     // Daniel Libera    -nazwa kh
          /*10*/  new ColumnText('BILL_KH_STREET','billing_street_address'),                    // Krótka 10
          /*11*/  new ColumnText('BILL_KH_HOUSE',NULL,false),
          /*12*/  new ColumnText('BILL_KH_POSTCODE','billing_postcode'),                        // 33-131
          /*13*/  new ColumnText('BILL_KH_CITY','billing_city'),                                // Łęg Tarnowski
          /*14*/  new ColumnCountryISO2('BILL_KH_COUNTRY',$this->GetISO2($this->orders_id)),    // PL
          /*15*/  new ColumnText('BILL_KH_VATID','customers_nip'),

            // dane odbiorcy
          /*16*/  new ColumnText('SHIP_KH_CODE','customers_id'),          
          /*17*/  new ColumnText('SHIP_KH_NAME','delivery_name'),
          /*18*/  new ColumnText('SHIP_KH_STREET','delivery_street_address'),
          /*19*/  new ColumnText('SHIP_KH_HOUSE',NULL,false),
                //new ColumnText('SHIP_KH_NUMBER',NULL,false),
          /*20*/  new ColumnText('SHIP_KH_POSTCODE','delivery_postcode'),
          /*21*/  new ColumnText('SHIP_KH_CITY','delivery_city'),
          /*22*/  new ColumnText('SHIP_KH_COUNTRY_NOTE',NULL,false),             // notatka

          /*23*/  new ColumnTextValue('DOC_TYPE',$this->DocType()),                  //0-paragon 1-faktura typ dokumentu (PAR, FVS, FVW, WDT, EXP)
          /*24*/  new ColumnVatUE('VATUE','zone',$this->VatUE()),
          /*25*/  new ColumnText('CURRENCY','currency'),	                     // waluta
          /*26*/  new ColumnCurrency('CURRENCY_VALUE','currency_value'),         // przelicznika waluty (nowe dodane pole)
          /*27*/  new ColumnOrderSource('ORDER_SOURCE','orders_source'),          // zródło zamówienia czy sklep(A),czy baselinker(B)
          /*28*/  new ColumnText('CUSTOMER_EMAIL','customers_email_address'),	 // adres email wysyłka faktury	
          /*29*/  new ColumnTextValue('CUSTOMER_NOTE',$this->Note()),
          /*30*/ new ColumnTextValue('SHIP_VAT',$this->GetShippingVat($this))
        
        );
        
    }

    public function ResetStatus($value)
    {
        $params = array
        (
			':maxkod_status'  => $value
        );

        $this->DB->NonMyQuery('UPDATE orders SET maxkod_status=:maxkod_status', $params);
    }

    public function SetStatus($id , $active)
    {
        $params = array
        (
          ':orders_id'      => $id,
          ':maxkod_status'  => $active
        );

        $this->DB->NonMyQuery('UPDATE orders SET maxkod_status=:maxkod_status WHERE orders_id=:orders_id', $params);
    }

    public function Alter()
    {
        $sql = 'ALTER TABLE orders ADD maxkod_status TINYINT NOT NULL';
        return $this->DB->NonMyQuery($sql, NULL);
    }

    public function AllTest()
    {
        $sql = 'SELECT * FROM orders_test';
        return $this->DB->MyQuery($sql, NULL, PDO::FETCH_CLASS, __CLASS__);
    }
    
    public function getResult()
    {
        $params = array
        (
          ':maxkod_status'  => 0
        );

        $sql = 'SELECT orders_id, 
        	date_purchased,
	        payment_method,
    	    customers_id, 
        	currency_value, 
	        invoice_dokument, 
	        billing_name,
    	    billing_street_address,
    	    billing_postcode,
    	    billing_city,
    	    billing_company,
    	    customers_nip,
    	    delivery_name,
    	    delivery_street_address,
    	    delivery_postcode,
    	    delivery_city,
    	    currency,
    	    orders_source,
    	    customers_email_address,
    	    payment_method_class,
    	    maxkod_shipping_module_id
        FROM orders LEFT JOIN modules_payment ON orders.maxkod_payment_module_id=modules_payment.id WHERE maxkod_status=:maxkod_status'; // LIMIT 10000';
     
        //$sql = "select * from products";

        $result = $this->DB->MyQuery($sql, $params);
        $result->setFetchMode(PDO::FETCH_CLASS,__CLASS__);
        
        return $result;
        
    }



}
