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

class FileSender extends Ftp
{
    
   function __construct($from, $to, $tmp_file, $remote_folder,  $host, $user_name, $user_pass, $port)
   {
      parent::__construct($host, $user_name, $user_pass, $port);
      $this->From = $from;
      $this->To = $to;
      $this->TmpFile = $tmp_file;
      $this->RemoteFolder = $remote_folder;
   }    
   
   public function Run()
   {
      return $this->Send($this->From, $this->To, $this->TmpFile, $this->RemoteFolder);
   }
     
}

