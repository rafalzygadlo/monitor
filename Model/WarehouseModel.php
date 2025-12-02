<?php

/**
 * warehouseModel
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
 use Model\ProductModel;
 use PDO;
 
class WarehouseModel extends Model
{
    
    function __construct()
    {  
        parent::__construct();
    }
    
    function GetQuantity($products_id)
    {
        $params = array
        (
			':products_id' => $products_id
		);

		return $this->DB->Row("SELECT products_id,products_quantity FROM products WHERE products_id=:products_id", $params, PDO::FETCH_OBJ);

    }
			
    function SetQuantity($products_id, $quantity)
    {
        $params = array
        (
		      ':products_id' => $products_id,
		      ':products_quantity'    => $quantity,
	      );

	      $sql = "UPDATE products SET products_quantity=:products_quantity WHERE products_id=:products_id";
	      return $this->DB->NonMyQuery($sql, $params, PDO::FETCH_OBJ);
    
    }
			
		function SetProductNoPrice()
    {

        $params = array
        (
          ':products_id'          => $this->products_id,
          ':products_quantity'    => $this->products_quantity,
          ':products_man_code'    => $this->products_man_code,
          
          //zmieniaj jednak cene hurtowa zawsze
          ':products_price_tax_2' => $this->products_price_tax_2,
          ':products_price_2'     => $this->products_price_2,
          ':products_tax_2'       => $this->products_tax_2,

          ':products_purchase_price' => $this->products_purchase_price
   
        );

        $sql = "UPDATE products SET products_man_code=:products_man_code, products_quantity=:products_quantity, 
        products_purchase_price=:products_purchase_price,
         /* cena 2 */
        products_price_tax_2=:products_price_tax_2,
        products_price_2=:products_price_2,
        products_tax_2=:products_tax_2,
         
        maxkod_update_symfonia=now() WHERE products_id=:products_id";

        //print_r($params);
        //sleep(10);
        $this->DB->NonMyQuery($sql, $params);

    }
			
		private function SetProductWithPrice()
    {
									
        $params = array
        (
          ':products_id'          => $this->products_id,
          ':products_quantity'    => $this->products_quantity,

					':products_price_tax' => $this->products_price_tax,
          ':products_price'     => $this->products_price,
          ':products_tax'       => $this->products_tax,

					':products_price_tax_2' => $this->products_price_tax_2,
          ':products_price_2'     => $this->products_price_2,
          ':products_tax_2'       => $this->products_tax_2,

          ':products_man_code'    => $this->products_man_code,
          /* ':products_adminnotes' => $this->products_adminnotes,*/
	        ':products_purchase_price' => $this->products_purchase_price
          /* ':products_ean'         => $this->products_ean */
        );

        //print_r($params);
        $sql = "UPDATE products SET /* products_adminnotes=:products_adminnotes, */ products_man_code=:products_man_code, /* products_ean=:products_ean, */
        products_quantity=:products_quantity,	products_purchase_price=:products_purchase_price,	maxkod_update_symfonia=now(),
	      /* cena 1 */
        products_price_tax=:products_price_tax,
        products_price=:products_price,
        products_tax=:products_tax,
	      /* cena 2 */
        products_price_tax_2=:products_price_tax_2,
        products_price_2=:products_price_2,
        products_tax_2=:products_tax_2

        WHERE products_id=:products_id";
        $this->DB->NonMyQuery($sql, $params);

  }

    function SetProductPrices($productPriceModel)
    {

    }

		
    function SetProduct($record)
    { 
      $id = $record[0];				      // id produktu
	    $price_net = $record[1];			// cena netto
	    $provider_code = $record[2];	// kod dostawcy
	    $ean = $record[3];					  // ean
	    $location = $record[4];				// lokalizacja
	    $quantity = $record[5];				// ilosc
        
      $this->productModel = new productModel();

	    $row = $this->productModel->GetTax($id);
	    if(!$row)
	    {
	    	FileLog::Write("get tax error: ".$id);
	    	print "error:".$id;
	    	sleep(5);
	    	return false;
	    }
    	
    	$tax = $row->tax_rate;

	    //cena 1
		  $row = $this->productModel->GetMargin($id);
		  // znaleziona cena w przeciwnym wypadku defaultowa marżą
		  if($row)
		  {
			  $margin_1 = ($price_net * $row->maxkod_margin) / 100;
			
		  }else{
			
			  $margin_1 = ($price_net * PRICE_MARGIN_1) / 100;
		  }
		
    	$price_net_1 = $price_net + $margin_1;

    	$price_tax_1 = ($price_net_1 * $tax) / 100;
    	$price_gross_1 = $price_net_1 + $price_tax_1;


	    //cena 2
    	$margin_2 = ($price_net * PRICE_MARGIN_2) / 100;
    	$price_net_2 = $price_net + $margin_2;

    	$price_tax_2 = ($price_net_2 * $tax) / 100;
    	$price_gross_2 = $price_net_2 + $price_tax_2;

        
	    $this->products_id          = $id;
    	$this->products_quantity    = $quantity;
        
		  $this->products_man_code    	= $provider_code;
    	$this->products_ean         	= $ean;
    	$this->products_adminnotes  	= $location;
		
		  // round to .00
		  $price_gross_1 = ceil($price_gross_1);
		  // wystarczy zakomentować będą ceny bez zaokrąglania
		 
			
		  //cena 1
		  $this->products_price_tax 	= $price_gross_1;
		  $this->products_price     	= $price_net_1;
		  $this->products_tax       	= $price_tax_1;

		  //cena 2
		  $this->products_price_tax_2 	= $price_gross_2;
	    $this->products_price_2     	= $price_net_2;
	    $this->products_tax_2       	= $price_tax_2;
			
		  // cena netto
		  $this->products_purchase_price  = $price_net;
		  
      // czy aktualizować ceny
      $row = $this->productModel->GetUpdatePrice($id);
		  $this->maxkod_update_price = $row->maxkod_update_price;
			
		  return true;
    }


    public function Set($record)
    {
      if($this->SetProduct($record))
      {
        if($this->maxkod_update_price)
        {
          print "with price";
          $this->SetProductWithPrice();
        }
        else
        {
          print "no price";
          $this->SetProductNoPrice();
        }
        return true;
      }
    }

}
