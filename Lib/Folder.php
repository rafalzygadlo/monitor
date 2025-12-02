<?php

/**
 * Folder
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2020 maxkod.pl
 * @version    1.0
 */


namespace Lib;

class Folder
{
    private $files = array();    
    private $folder;
    
    public function __construct($folder)
    {
    	$this->folder = $folder;
    }
        
  
    public function Clear($path)
	{
		
        $files = glob($path.'/*'); // get all file names
		
        foreach($files as $file)
		{ // iterate files
            //print $file;
            //print "<br>";
			unlink($file); // delete file
		}
	}

  
    public function getFiles($dir)
    {
        
        if (is_dir($dir) )
        {
            if ($dh = opendir($dir))
            {
                while (($file = readdir($dh)) !== false)
                {
                    if(($file != ".") && ($file != ".."))
                    {
                        $path = $dir.DIRECTORY_SEPARATOR.$file;
                        $this->getFiles($path);
                        if(is_file($path))
                        {
                            $fileName = new FileName();
                            $fileName->path = $path;
                            $fileName->relpath = substr($path,strlen($this->folder) + 1,strlen($path));
                            array_push($this->files, $fileName );
                        }
                    }
                }
                
                closedir($dh);
            }
        
            return $this->files;
        
        }
    
    }
}

?>