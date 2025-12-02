<?php


namespace Lib;

use Lib\Zip;

class Update
{


	private function Download()
	{
		$file = file_get_contents('http://download.maxkod.pl/mass-zone/symfonia.zip');
		file_put_contents("Backup/symfonia.zip",$file);
	}

	private function Backup($folder, $archive_name, $ext)
	{
		$zip = new Zip();
		if($zip->CompressFolder($folder, $archive_name, $ext))
			return true;
		else
			return false;
	}

	private function BackupFiles($files, $folder)
	{
		foreach($files as $file)
		{
			@mkdir(dirname($folder."/".$file));
			copy($file, $folder."/".$file);
		}
	}

	private function ExtractFiles()
	{
		$zip = new Zip();
		if($zip->ExtractArchive("Backup/symfonia.zip",getcwd()))
			return true;
		else
			return false;	
	}

	public function Run($folder, $ext)
	{
		
		@mkdir("Backup");
		$date = date("Y_m_d_H_i_s");
		
		//backup to zip file
		if(!$this->Backup($folder, "Backup/backup-$date.zip", $ext))
		{
			print "backup error";
			return;
		}
		
		//download
		$this->Download();
			
		//update files
		if(!$this->ExtractFiles())
		{
			print "extract error";
			return;
		}
		
		
		print "Updated succesfully";
		
		

		
	}



}



?>