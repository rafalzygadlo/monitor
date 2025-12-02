<?php

/**
 * reportModel
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
 
class ReportModel extends Model
{
    public $date;
    private $sql;
        
    public function GetId()
    {
        return $this->id;
    }
    
    public function GetName()
    {
        return $this->name;
    }
    
    public function AddItemSql($report_id, $product_id, $product_qt, $record_qt)
    {
      //print $report_id."\n";
      //print $product_id."\n";
      //print $record_qt."\n";
      //print $product_qt."\n";
      
       $this->sql .= "INSERT INTO maxkod_report_products VALUES('',".$report_id.",".$product_id.",".$product_qt.",".$record_qt.");";
    }
    
    public function RunAddItemSql()
    {
        print $this->sql;
        return $this->DB->NonMyQuery($this->sql, NULL, PDO::FETCH_CLASS, __CLASS__);
    }
    
    public function Insert()
    {
        $params = array
        (
          ':date'  => $this->date
        );
      
        $sql = 'INSERT INTO maxkod_report SET date=:date';
        return $this->DB->NonMyQuery($sql, $params, PDO::FETCH_CLASS, __CLASS__);
      
    }
    
}
