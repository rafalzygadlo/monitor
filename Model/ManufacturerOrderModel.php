<?php

/**
 * orderModel
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
 
class ManufacturerOrderModel extends Model
{
    
    function __construct()
    {  
        parent::__construct();
    }
  
    public function fetchAll()
    {
        $sql = "SELECT *                    
                FROM products p, manufacturers m 
                WHERE p.manufacturers_id = m.manufacturers_id
                ORDER BY p.products_id";
        
        $result = $this->DB->MyQuery($sql, NULL);

        return $result->fetchAll(PDO::FETCH_CLASS);
    }

}
