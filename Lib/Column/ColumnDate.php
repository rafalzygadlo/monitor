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


class ColumnDate extends Column
{

    public function Render($item)
    {
        $name = $this->FieldName;
        return date("Y-m-d",strtotime($item->$name));
    }

}

