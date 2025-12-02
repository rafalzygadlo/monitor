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

use Lib\Ftp;

class FileReceiver extends Ftp
{
    
   function __construct($from, $to, $arch, $host, $user_name, $user_pass, $port)
   {
      parent::__construct($host, $user_name, $user_pass, $port);
      $this->From = $from;
      $this->To = $to;
      $this->Arch = $arch;
   }    
   
   public function Run()
   {
      $this->ReceiveFolder($this->From, $this->To, $this->Arch);
   }
     
}

