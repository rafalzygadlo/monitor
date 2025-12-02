<?php

/**
 * orderItemModel
 * 
 * @category   Model
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */

namespace Model;
 
use Lib\Model;
use Lib\Column\ColumnText;
use Lib\Column\ColumnTextValue;
use Lib\Column\ColumnPriceDiscount;
use Lib\Column\ColumnCountryVat;
use Lib\Column\ColumnProductType;
use Model\CountryModel;
use Model\ProductTypeModel;
use PDO;
 
class OrderItemModel extends Model
{

	function __construct()
    {
      parent::__construct();
	  $this->Discount = 0;
	  $this->CountryModel = new CountryModel();
	  $this->ProductTypeModel = new ProductTypeModel();
	  
    }

	// rabaty
    private function Redemption($id)
    {
		//rabat kwotowy
		$params = array
        (
		  ':orders_id' 	=> $id,
		  ':class'		=>'ot_redemptions'
        );

		$sql = 'SELECT * FROM orders_total WHERE orders_id=:orders_id AND class=:class';
        $row = $this->DB->Row($sql, $params, PDO::FETCH_CLASS, __CLASS__);
								
		// może nie być rekordu dlatego sprawdzamy
		if($row)
		  return $row->value;
		else
		  return 0;
    
	}

	// kod rabatowy w procentach
	private function Coupon($id)
    {
		//rabat kwotowy
		$params = array
        (
		  ':orders_id' 	=> $id,
		  ':class'		=>'ot_discount_coupon'
        );

		$sql = 'SELECT * FROM orders_total WHERE orders_id=:orders_id AND class=:class';
        $row = $this->DB->Row($sql, $params, PDO::FETCH_CLASS, __CLASS__);
								
		// może nie być rekordu dlatego sprawdzamy
		if($row)
		  return $row->value;
		else
		  return 0;
	}

	private function SubTotal($id)
	{
		//cena
		$params = array
        (
          ':orders_id' 	=> $id,
		  ':class'		=>'ot_subtotal'
        );

		$sql = 'SELECT * FROM orders_total WHERE orders_id=:orders_id AND class=:class';
        $row = $this->DB->Row($sql, $params, PDO::FETCH_CLASS, __CLASS__);
		return $row->value;
								
	}
	
    public function Items($order)
    {

	  	// obliczenia rabatów
		$redemption = $this->Redemption($order->orders_id);
	  	$coupon = $this->Coupon($order->orders_id);
	  	$subtotal = $this->SubTotal($order->orders_id);

	  	$discount = ($redemption / $subtotal);
	  	$discount += ($coupon / $subtotal);


	  	// vat dla krajów
	  	

		//typy produktów
		$product_types = $this->ProductTypeModel->fetchAll();

	  	$params = array
      	(
        	':orders_id' => $order->orders_id
      	);

    	$sql = 'SELECT orders_id, op.products_id, p.products_ean , op.products_name, m.manufacturers_name, p.products_weight, op.products_quantity, op.final_price_tax, pjd.products_jm_name, p.products_type, op.products_tax, 
		p.maxkod_margin
		FROM orders_products op
		LEFT JOIN products p on op.products_id = p.products_id
		LEFT JOIN manufacturers m on p.manufacturers_id = m.manufacturers_id
		LEFT JOIN products_jm_description pjd on p.products_jm_id = pjd.products_jm_id AND pjd.language_id = 1
		WHERE orders_id=:orders_id';

		$result = $this->DB->MyQuery($sql, $params, PDO::FETCH_CLASS, __CLASS__);
    	$rows = $result->fetchAll(PDO::FETCH_CLASS, __CLASS__);

		// tutaj ustaw dla każdego itema discount
		foreach($rows as $row)
		{
			if($order->RegisteredVat)
				$vat = 0;
			else
				$vat = $this->CountryModel->GetVat($order->orders_id, $row->products_tax);

	  		//definicja kolumn dla
	  		$row->Columns = array
	  		(
				//new ColumnText('ID','orders_products_id'),
				new ColumnText('TW_CODE','products_id'),
				new ColumnText('TW_NAME','products_name'),
				new ColumnText('JM','products_jm_name'),
				new ColumnProductType('JM','products_type',$product_types),
				new ColumnTextValue('TAX',$vat),
				new ColumnText('EAN','products_ean'),
				new ColumnText('WEIGHT','products_weight'),
				new ColumnText('MANUFACTURER','manufacturers_name'),
				new ColumnText('COUNT','products_quantity'),
				new ColumnPriceDiscount('PRICE_BRUTTO','final_price_tax', $discount),
				new ColumnText('MARGIN','maxkod_margin')
				//new ColumnText('TAX','orders_id')
	  		);

		}

		return $rows;
    }

}
