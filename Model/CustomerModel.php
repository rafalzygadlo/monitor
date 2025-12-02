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
 
class CustomerModel extends Model
{
    
    function __construct()
    {  
        parent::__construct();  
    }
    
    public function All()
    {
        $sql = "SELECT * FROM customers";
        return $this->DB->MyQuery($sql, NULL, PDO::FETCH_CLASS, __CLASS__);
    }

}
