<?php

/**
 * ColumnCurrency
 * 
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;


class ColumnCurrency extends Column
{

    public function Render($item)
    {
        $name = $this->FieldName;
        $str = 1.0 / $item->$name;
        return $str;
    }

}

