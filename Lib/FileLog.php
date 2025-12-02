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


namespace Lib;


class FileLog
{

   public static function Write($data)
   {
      $fname = LOG_FOLDER."/".date("Y_m_d").".log";
      $log =  date("Y-m-d H:i:s")." ".$data."\n";
      file_put_contents($fname, $log, FILE_APPEND | LOCK_EX);
   }


}

