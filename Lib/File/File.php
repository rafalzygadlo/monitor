<?php

/**
 * File
 * 
 * @category   Lib
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib\File;

use Lib\FileLog;
class File
{
   
   private     $Separator = FILE_DATA_SEPARATOR;
   //protected   $Folder = DEFAULT_FILE_FOLDER;
   public      $File;
   public      $NewFile;   //new file name after md5 and rename
    
   function __construct($file, $folder, $separator)
   {
        $this->File = $folder.'/'.$file;
        $this->NewFile = $file;
        $this->Folder = $folder;
		$this->Separator = $separator;
   }
   
   public function MD5()
   {
      return md5_file($this->File); 
   }
   
   public function Rename()
   {
      $this->NewFile = $this->MD5($this->File);
      $new_file_path = $this->Folder.'/'.$this->NewFile;
      if(rename($this->File, $new_file_path))
         $this->File = $new_file_path;
   }
   
   public function Delete()
   {
      @unlink($this->File);
   } 
   
   public function WriteSeparator()
   {
      file_put_contents($this->File, $this->Separator, FILE_APPEND | LOCK_EX);
   }
   
   private function ReplaceString($item)
   {
      if($item == null)
         return null;
      
      $pos = strpos($item,";");
      if($pos)
      {
         print $item = str_replace(";","",$item);
         FileLog::Write(" ; replaced with: ".$item);
      }

      $pos = strpos($item,"\n");
      if($pos)
      {
         print $item = str_replace("\n"," ",$item);
         FileLog::Write("newline replaced");
      }

      $pos = strpos($item,"\r");
      if($pos)
      {
         print $item = str_replace("\r"," ",$item);
         FileLog::Write("newline replaced");
      }

      return $item;
   }

   public function WriteItem($item)
   {
      $item = $this->ReplaceString($item);
      $data = @iconv("UTF-8","Windows-1250//TRANSLIT", $item);
      file_put_contents($this->File, $data, FILE_APPEND | LOCK_EX);
   }

   public function WriteEOL()
   {
      file_put_contents($this->File, "\n", FILE_APPEND | LOCK_EX);
   }

}

