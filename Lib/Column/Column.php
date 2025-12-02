<?php

/**
 * Column
 * 
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;

abstract class Column
{

    public $Name;               // Nazwa columny (tytuł)
    public $FieldName;          // Nazwa pola
    public $Visible = true;

    public function __construct($name, $fieldname, $visible = true)
    {
        $this->Name = $name;
        $this->FieldName = $fieldname;
        $this->Visible = $visible;
    }

}
