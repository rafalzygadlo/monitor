<?php
/**
 * ShippingVat
 * 
 * @category   Model
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2021 maxkod.pl
 * @version    1.0
 */

 namespace Model\Order;

 use Lib\Model;
 use Model\CountryModel;
 use Model\Order\ShippingVat;
 use PDO;
 
class ShippingVat extends Model
{
    
    function __construct()
    {    
        parent::__construct(); 
    }

    public function Get($orders_id)
    {
    	$params = array
        (
		  ':orders_id' 	=> $orders_id,
        );

		$sql = 'SELECT MAX(products_tax) as value FROM orders_products WHERE orders_id=:orders_id';
        $row = $this->DB->Row($sql, $params, PDO::FETCH_OBJ);

		// może nie być rekordu dlatego sprawdzamy
		if($row)
			$value = $row->value;
		else
			$value = NULL;
		
		return $value;

    }

}
