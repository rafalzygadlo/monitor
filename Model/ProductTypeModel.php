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
 
class ProductTypeModel extends Model
{
    
    function __construct($id = 0, $name = '')
    {  
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
      
    }
    
    public function GetId()
    {
        return $this->id;
    }
    
    public function GetName()
    {
        return $this->name;
    }
        
    /*
    <select name="rodzaj_produktu" style="width: 200px; height: auto; padding: 8px 5px;" id="rodzaj_produktu" class="valid">
        <option value="standard" selected="selected">standardowy</option>
        <option value="indywidualny">niestandardowy, indywidualny</option>
        <option value="usluga">usługa</option>
        <option value="online">treść cyfrowa</option>
    </select> 
    */  
    
    public function fetchAll()
    {
        return array
        (
            new productTypeModel(0,"standard"),
            new productTypeModel(1,"usluga"),
            new productTypeModel(2,"indywidualny"),
            new productTypeModel(3,"online"),
       
        );    
        
    }

}
