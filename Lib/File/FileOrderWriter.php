<?php

/**
 * File
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib\File;

use Lib\File\File;

class FileOrderWriter extends FileWriter
{

   function __construct($file, $folder, $header, $items, $separator)
   {
         parent::__construct($file, $folder, $header, $items, $separator);

   }

}

