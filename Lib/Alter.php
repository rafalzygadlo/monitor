<?php

/**
 * Alter
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib;


class Alter
{

    public $db;
    public $items;
   
    public function __construct()
    {
        $this->db = Database::getInstance();
        
        $this->items = array(
        "ALTER TABLE `manufacturers` ADD `maxkod_margin` SMALLINT NOT NULL AFTER `manufacturers_image`",
        "ALTER TABLE `orders` ADD `maxkod_status` TINYINT NOT NULL",
        "ALTER TABLE `orders` ADD `maxkod_shipping_module_id` INT NOT NULL",
        "ALTER TABLE `orders` ADD `maxkod_payment_module_id` INT NOT NULL",
        "ALTER TABLE `orders` ADD `maxkod_country_id` INT NOT NULL",
        "ALTER TABLE `products` ADD `maxkod_status` TINYINT NOT NULL",
        "ALTER TABLE `products` ADD `maxkod_margin` SMALLINT NOT NULL AFTER `maxkod_status`;",
        "ALTER TABLE `products` ADD `maxkod_update_symfonia` DATETIME NOT NULL AFTER `maxkod_margin`;",
        "ALTER TABLE `products`  ADD `maxkod_update_cms` DATETIME NOT NULL  AFTER `maxkod_margin`;",
        "ALTER TABLE `products` ADD `maxkod_update_price` TINYINT NOT NULL AFTER `maxkod_update_symfonia`",
        "ALTER TABLE `products` ADD `products_price_2` DECIMAL(15,2) NOT NULL;",
        "ALTER TABLE `products` ADD `products_price_tax_2` DECIMAL(15,2) NOT NULL;",
        "ALTER TABLE `products` ADD `products_tax_2` DECIMAL(15,2) NOT NULL;",
        "ALTER TABLE `products` ADD `products_old_price_2` DECIMAL(15,2) NOT NULL;",
        "ALTER TABLE `products` ADD `products_retail_price_2` DECIMAL(15,2) NOT NULL;",
        "CREATE TABLE `maxkod_report`
        (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `date` DATETIME NOT NULL ,
            PRIMARY KEY (`id`)
        ) ENGINE = InnoDB;",
        "CREATE TABLE `maxkod_report_products`
        (
            `id` INT NOT NULL AUTO_INCREMENT ,
            `id_maxkod_report` INT NOT NULL ,
            `id_product` INT NOT NULL ,
            `old_quantity` INT NOT NULL ,
            `new_quantity` INT NOT NULL ,
            PRIMARY KEY (`id`)
            ) ENGINE = InnoDB;"
        
        
        
        );
        
    }

    public function run()
    {
        print "<table border=1 cellpadding=4 cellspacing=4>";
        foreach($this->items as $item)
        {
            print "<tr>";
            print "<td>".$item."</td>";
            print "<td>";
            print $this->db->nonMyQuery($item,null);
            print "</td>";
            print "</tr>";
        }
        
        print "<table>";
    }
    
}
    
    