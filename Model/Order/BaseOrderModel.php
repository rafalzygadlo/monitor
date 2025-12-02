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
 use Model\CountryModel;
 use Model\Order\ShippingVat;
 use PDO;
 
class BaseOrderModel extends Model
{

    function __construct()
    {
        parent::__construct(); 
        $this->ShippingVat = new ShippingVat();
		$this->CountryModel = new CountryModel();
    }

    protected function Note()
    {
    	$params = array
        (
		  ':orders_id' 	=> $this->orders_id,
        );

		$sql = 'SELECT * FROM orders_to_extra_fields WHERE orders_id=:orders_id AND fields_id=1';
        $row = $this->DB->Row($sql, $params, PDO::FETCH_CLASS, __CLASS__);

		// może nie być rekordu dlatego sprawdzamy
		if($row)
			$value = $row->value;
		else
			$value = NULL;
			
		return $value;

    }

	protected function GetISO2($orders_id)
	{
		$country = new CountryModel();
		return  $country->GetISO2($orders_id);
	}

	/*
	vat for header order
	*/
	protected function GetShippingVat($order)
	{
		//czy zarejestrowany nip w ue
		if($this->RegisteredVat)
			return 0;

		//sprawdz vat w tabeli
		$vat = $this->ShippingVat->Get($order->orders_id);
		//znajdz w licie panstw
		$vat = $this->CountryModel->GetVat($order->orders_id, $vat);

		return $vat;

	}
	
	//czy kraj w UE oprocz POLSKI
	protected function CountryInUE()
	{		
        $params = array
        (
            ':orders_id'  => $this->orders_id,
        );
	
		$sql = 'SELECT orders_id, zone FROM orders 
		LEFT JOIN customers ON orders.customers_id = customers.customers_id
		LEFT JOIN address_book ON address_book.customers_id = customers.customers_id
		LEFT JOIN countries ON address_book.entry_country_id = countries.countries_id
		WHERE orders_id=:orders_id';
		
		$row = $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
		$ue = $row->zone;
	
		if($ue == 'u')
		    return true;
		else
			return false;

	}
	
    protected function DocType()
    {
			
        $params = array
        (
            ':orders_id'  => $this->orders_id,
        );
	
		$sql = 'SELECT orders_id, billing_country, zone, currency from orders 
		LEFT JOIN customers ON orders.customers_id = customers.customers_id
		LEFT JOIN address_book ON address_book.customers_id = customers.customers_id
		LEFT JOIN countries ON address_book.entry_country_id = countries.countries_id
		WHERE orders_id=:orders_id';
		
		$row = $this->DB->Row($sql, $params, PDO::FETCH_OBJ);

		$ue = $row->zone;
		
		//zarejestrowany VAT w UE
		if($this->RegisteredVat)
			return "WDT";
		
		// nie UE i nie PL
		// u = UE f = Polska
		if($ue != 'u' && $ue != 'f')
		{
		    return "DEX";
		}

		// waluta PLN i unia europejska
		if($row->currency == 'PLN')
		    return "FVS";
		else
		    return "FVW";
    }	

    protected function VatUE()
    {
        $params = array
        (
            ':orders_id'  => $this->orders_id,
        );
    
		$sql = "SELECT zone from orders 
				LEFT JOIN customers ON orders.customers_id = customers.customers_id
				LEFT JOIN address_book ON address_book.customers_id = customers.customers_id
				LEFT JOIN countries ON address_book.entry_country_id = countries.countries_id
				WHERE orders_id=:orders_id";
		     
        return $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
    }
   
    protected function PKWIU()
    {
        $params = array
        (
            ':shipping_module'  => $this->maxkod_shipping_module_id,
            ':key'              => "WYSYLKA_PKWIU", 
        );
        
        
        $sql = "SELECT * FROM modules_shipping, modules_shipping_params 
        WHERE modules_shipping.id =:shipping_module 
        AND modules_shipping_params.kod =:key 
        AND modules_shipping.id = modules_shipping_params.modul_id";
        
     
        return $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
    }
   
    protected function KOSZT_WYSYLKI()
    {
        
        // zmiana dane z tabeli orders total
        // źle liczyło, nie uwzględniało darmowej wysyłki
		$params = array
        (
		  ':orders_id' 	=> $this->orders_id,
		  ':class'		=>'ot_shipping'
        );

		$sql = "SELECT * FROM orders_total WHERE orders_id=:orders_id AND class=:class";
        $row = $this->DB->Row($sql, $params, PDO::FETCH_CLASS, __CLASS__);
								
		// może nie być rekordu dlatego sprawdzamy
		if($row)
		   return $row->value;
		else
		   return 0;
	
    }
   
    protected function KOSZT_PLATNOSCI()
    {
        
        $params = array
        (
		  ':orders_id' 	=> $this->orders_id,
		  ':class'		=>'ot_payment'
        );

		$sql = "SELECT * FROM orders_total WHERE orders_id=:orders_id AND class=:class";
        $row = $this->DB->Row($sql, $params, PDO::FETCH_CLASS, __CLASS__);

		// może nie być rekordu dlatego sprawdzamy
		if($row)
			return $row->value;
		else
			return 0;

    }

    // kwota darmowej wysylki
    protected function DARMOWA_WYSYLKA_KWOTA()
    {
        $params = array
        (
            ':id'  => $this->maxkod_payment_module_id,
            ':key' => "WYSYLKA_DARMOWA_WYSYLKA"
        );

        $sql = "SELECT * FROM  modules_shipping_params WHERE modules_shipping_params.kod =:key AND modules_shipping_params.modul_id=:id";

        $row = $this->DB->Row($sql, $params, PDO::FETCH_CLASS, __CLASS__);
        return $row->wartosc;

    }

	/*
	 * which field to use
	**/
	protected function BillingName()
	{
		$this->invoice_dokument;
		$value = $this->billing_name .' '. $this->billing_company;
		return $value;					
	}


}
