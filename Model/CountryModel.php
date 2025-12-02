<?php

/**
 * countryModel
 * 
 * @category   Model
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */

 namespace Model;
 
 use Lib\Model;
 use Lib\FileLog;
 use PDO;
 
class CountryModel extends Model
{
	private $code;
    
	function __construct()
    {  
        parent::__construct();  
		$this->code = array(
			"DE" => 7,
			"GB" => 20,
			"RO" => 9,
			"LT" => 5,  //litwa
			"BE" => 6, 	//belgia: 6,
			"BG" => 9,	//bułgaria: 9%
			"EE" => 9,	//estonia 9%
			"GR" => 13,	//grecja 13%
			"HR" => 13,	//chorwacja 13%
			"CY" => 5,	//cypr 5%
			"LV" => 5,	//Łotwa 5%
			"LU" => 3,	//luksemburg: 3%
			"HU" => 5,	//węgry: 5%
			"MT" => 5,	//malta: 5%
			"NL" => 9,	//niderlandy: 9%
			"AT" => 10,	//austria: 10%
			"PT" => 6,	//Portugalia: 6%
			"SI" => 9.5,//Słowenia 9,5%
			"SK" => 10,	//słowacja 10%
			"FI" => 14,	//finlandia 14%
			"FR" => 10,	//Francja - 10%
			"SE" => 12,	//Szwecja - 12%
			"CZ" => 15,	//Czechy: 15%
			"DK" => 25,	//Dania: 25%
			"IT" => 10,	//Włochy: 10%
			"IE" => 9,	//Irlandia: 9%
			"ES" => 10	//Hiszpania 10%
		);

    }
	
	public function GetVat($orders_id, $vat)
	{
		$code = $this->GetISO2($orders_id);
		
		if(array_key_exists($code, $this->code))
			$value = $this->code[$code];
		else
			$value = $vat;

		//print $code." vat country:".$value ."\n";	
		return $value;	
	}
    
    public function GetISO2($orders_id)
	{
		
        $params = array
        (
            ':orders_id'  => $orders_id,
        );
        
    	    $sql = 'SELECT countries_iso_code_2 FROM orders 
            LEFT JOIN customers ON orders.customers_id = customers.customers_id
			LEFT JOIN address_book ON address_book.customers_id = customers.customers_id
			LEFT JOIN countries ON address_book.entry_country_id = countries.countries_id
			WHERE orders_id=:orders_id';		
             
        $row = $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
		
		
		if($row)
		{
			return $row->countries_iso_code_2;
			
		}else{
			
			FileLog::Write("country ISO 2 not found: ".$orders_id);
			return null;
			
		}
	   
	}

}
