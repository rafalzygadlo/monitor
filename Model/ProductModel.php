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
 
class ProductModel extends Model
{
    
    function __construct()
    {  
        parent::__construct();
       
    }
    
    public function ResetStatus()
    {
        $params = array
        (
          ':maxkod_status'  => 0
        );

        $this->DB->NonMyQuery('UPDATE products SET maxkod_status=:maxkod_status', $params);
    }
    
    public function SetStatus()
    {
        $params = array
        (
          ':products_id'     => $this->products_id,
          ':maxkod_status'   => true
        );

        $this->DB->NonMyQuery('UPDATE products SET maxkod_status=:maxkod_status WHERE products_id=:products_id', $params);
    }
    
    public function GetUpdatePrice($products_id)
    {
        $params = array
        (
          ':products_id' => $products_id
        );

        $sql = 'SELECT maxkod_update_price
        FROM products
        WHERE products.products_id=:products_id';

        return $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
    }
/*
    stara funkcja marża pobierana z producentów
    public function GetMargin($products_id)
    {
        $params = array
        (
          ':products_id' => $products_id
        );

        $sql =
        'SELECT maxkod_margin
        FROM products, manufacturers
        WHERE
        products.manufacturers_id=manufacturers.manufacturers_id
        AND
        products.products_id=:products_id';
        
        return $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
    }
*/
    public function GetMargin($products_id)
    {
        $params = array
        (
          ':products_id' => $products_id
        );

        $sql = 'SELECT maxkod_margin FROM products WHERE products.products_id=:products_id';
        return $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
    }


    
    public function GetTax($products_id)
    {
        $params = array
        (
          ':products_id' => $products_id
        );

        $sql ='SELECT tax_rates.tax_rate FROM products LEFT JOIN tax_rates ON tax_rates.tax_rates_id = products.products_tax_class_id WHERE products.products_id=:products_id';
        return $this->DB->Row($sql, $params, PDO::FETCH_OBJ);
    }

    public function Alter()
    {
        $sql = 'ALTER TABLE products ADD maxkod_status TINYINT NOT NULL';
        return $this->DB->NonQuery($sql, NULL);
    }
    
    public function Ean()
    {
        $sql =
        'SELECT products.products_id,
                products_name,
                products_jm_name,
                products_type,
                tax_rates.tax_rate,
                products_ean, 
                products_weight, 
                manufacturers_name,
                products.maxkod_margin 
        FROM products
        LEFT JOIN products_description ON products.products_id = products_description.products_id
        AND products_description.language_id = 1
        LEFT JOIN products_jm_description ON products.products_jm_id = products_jm_description.products_jm_id
        LEFT JOIN tax_rates ON tax_rates.tax_rates_id = products.products_tax_class_id
        LEFT JOIN manufacturers ON products.manufacturers_id = manufacturers.manufacturers_id
        WHERE products_jm_description.language_id = 1';
        
        $result = $this->DB->MyQuery($sql, NULL);

        return $result->fetchAll(PDO::FETCH_CLASS,__CLASS__);
    }

    
    public function fetchAll()
    {
        $params = array
        (
          ':maxkod_status'   => false
        );
        
        $sql =
        'SELECT 
        	products.products_id,
        	products_name,
        	products_jm_name,
        	products_type,
        	tax_rates.tax_rate,
        	products_ean,
        	products_weight,
        	manufacturers_name,
          products.maxkod_margin 
        FROM products
        LEFT JOIN products_description ON products.products_id = products_description.products_id
        AND products_description.language_id = 1
        LEFT JOIN products_jm_description ON products.products_jm_id = products_jm_description.products_jm_id
        LEFT JOIN tax_rates ON tax_rates.tax_rates_id = products.products_tax_class_id
        LEFT JOIN manufacturers ON products.manufacturers_id = manufacturers.manufacturers_id
        WHERE products_jm_description.language_id = 1
        AND products.maxkod_status=:maxkod_status';
        
        $result = $this->DB->MyQuery($sql, $params);

        return $result->fetchAll(PDO::FETCH_CLASS,__CLASS__);
    }

}
