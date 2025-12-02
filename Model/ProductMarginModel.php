<?php

/**
 * ProductMarginModel
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
 
class ProductMarginModel extends Model
{
    
    function __construct()
    {  
        parent::__construct();
       
    }
    
    public static function checkLastOrderDate($product)
    {
        $counter = 0;
        $never = 0;
        $promo_counter = 0;
        $id = 0;
        $margin_promo = 20;
        $promo  = 0;

        $products_id = $product->products_id;
        $margin = $product->maxkod_margin;
        $promo = 0;
            
        if($product->last_date_purchased == null)
        {
            printf("%d, %s\n",$id, $product->last_date_purchased);
            $never++;
            $id++;
            return;
        }

        $date1 = time();
        $date2 = $product->last_date_purchased;
        $diff = abs($date1 - strtotime($date2));
        $days = floor($diff / (60*60*24));
               
        if($days > 30)
        {
            $new_margin = $margin - (floor($days / 30) * 5);
            if($margin_promo >= $new_margin)
            {
                $promo = 1;
                $new_margin = $margin_promo;
                $promo_counter++;
            }

            $counter++;
    
        }
 
        return $product;
    }




    public function getResult($fetchMode = PDO::FETCH_CLASS, $class = __CLASS__)
    {
      
        $sql =
        'SELECT 
        products.products_id, 
        max(date_purchased) last_date_purchased, 
        maxkod_margin 
        FROM products 
        LEFT JOIN orders_products ON products.products_id = orders_products.products_id 
        LEFT JOIN orders ON orders.orders_id = orders_products.orders_id 
        WHERE products.maxkod_update_price  = 1
        GROUP BY products.products_id  
        ORDER BY `products`.`products_id` ASC';

        
        $result = $this->DB->MyQuery($sql, null);
        $result->setFetchMode($fetchMode, $class);
        return $result;
    
    }

}
