<?php

namespace Lib;

use ZipArchive;

class Zip
{
    
	public function ExtractArchive($archive_name, $folder)
	{
				
		$zip = new ZipArchive();
		
		if ($zip->open($archive_name)) 
		{
			$zip->extractTo($folder);
			$zip->close();
			return true;
		} 
		else 
		{
			return false;
		}
		
	
	}



	public function CompressFolder($folder_name, $archive_name, $ext = array())
	{
		$folder = new Folder($folder_name);
		$files = $folder->getFiles($folder_name);
				
		$zip = new ZipArchive();
		if($zip->open(getcwd(). DIRECTORY_SEPARATOR. $archive_name, ZipArchive::CREATE | ZipArchive::OVERWRITE))
		{
			foreach($files as $file)
			{
				$this->AddFile($zip, $file, $ext);
			}
			
			$zip->close();		
			return true;
		}
		else
		{
			return false;
		}
	
	}

	private function AddFile($zip, $file, $ext)
	{
		
		foreach($ext as $ename)
		{
			$path = pathinfo($file->relpath);
			if(is_array($path))
			{	
				if(@$path["extension"] == $ename)
				{
					$zip->addFile($file->path, $file->relpath);
				}
			}
		}
	}
	


}