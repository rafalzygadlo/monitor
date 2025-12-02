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
 use PDO;
 
class MaxkodPaymentMethodModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }
    
    public function Get($payment_method)
    {
  		$params = array
    	(
    		':payment_method' => $payment_method
    	);
    
		return $this->DB->MyQuery("SELECT * FROM maxkod_payment_method, modules_payment WHERE payment_method=:payment_method AND modules_payment_id=modules_payment.id", $params, PDO::FETCH_OBJ);
    
    }
    
 
 	public function Insert($payment_method)
 	{
 		if(!$this->Exists($payment_method))
 		{
 	
 	  		$params = array
    		(
    			':payment_method' => $payment_method
    		);

 			$this->DB->NonMyQuery("INSERT INTO maxkod_payment_method SET payment_method=:payment_method", $params);
 		}
 		
 	}

    private function Exists($payment_method)
    {

  		$params = array
   		(
   			':payment_method' => $payment_method
   		);

    	$row = $this->DB->Row("SELECT * FROM maxkod_payment_method WHERE payment_method=:payment_method",$params);
    	if($row)
    		return true;
    	else
    		return false;

    }

}
