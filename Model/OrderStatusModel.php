<?php
/**
 * orderStatusModel
 * 
 * @category   Model
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2020 maxkod.pl
 * @version    1.0
 */

 namespace Model;
 
 use Lib\Model;
 use Lib\Column\ColumnText;
 use PDO;
 
class OrderStatusModel extends Model
{
    
    function __construct()
    {    
        parent::__construct();
    }
      
    public function fetchAll()
    {
        
        $sql = 'SELECT orders.orders_id, date_purchased, orders_status, orders_shipping_number 
        FROM orders
        LEFT JOIN orders_shipping on orders.orders_id=orders_shipping.orders_id
        WHERE date_purchased BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() + INTERVAL 1 DAY';
        
        $result = $this->DB->MyQuery($sql, NULL, PDO::FETCH_OBJ);
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

}
