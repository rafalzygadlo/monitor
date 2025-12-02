<?php

/**
 * ColumnText
 * 
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */

namespace Lib\Column;


class ColumnText extends Column
{

    public function Render($item)
    {
        $name = $this->FieldName;
        //$str = iconv("UTF-8","ISO-8859-2//TRANSLIT", $item->$name);
        $str = $item->$name;

        return $str;
    }

}

