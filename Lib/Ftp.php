<?php 

/**
 * Ftp
 * 
 * @category   Libs
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib;

use Lib\FileLog;

class Ftp
{
    
    // lista lokalnych pobranych plików
    public $LocalFiles;
    
    function __construct($host, $user_name, $user_pass, $port)
    {
        $this->LocalFiles = array();
        $this->RemoteFiles = array();
        $this->Host = $host;
        $this->UserName = $user_name;
        $this->Password = $user_pass;
        $this->Port = $port;
    }
    
    private function Connect()
    {
        //print 'connect<br>';
        return ftp_connect($this->Host, $this->Port, 2);
    }
    
    private function SSLConnect()
    {
        //print 'SSL connect<br>';
        return ftp_ssl_connect($this->Host, $this->Port, 2);
    }


    private function Login()
    {
        //print 'login<br>';
        return ftp_login($this->ConnId, $this->UserName, $this->Password); 
    }
    
    private function Rename($from, $to)
    {
        return ftp_rename($this->ConnId, $from, $to);
    }
    
    private function Passive()
    {
        //print 'passive<br>';
        //ftp_set_option($this->ConnId, FTP_USEPASVADDRESS, false);
        ftp_pasv($this->ConnId, true);
    }
    
    private function Chdir($folder)
    {
        //print 'chdir<br>';
        return ftp_chdir($this->ConnId,$folder);        
    }
    
    private function Put($local_file, $remote_file)
    {
        //print 'put<br>'; 
        //print $remote_file.'<br>';
        //print $local_file.'<br>';
        return ftp_put($this->ConnId, $remote_file, $local_file, FTP_BINARY); 
    }

    private function Get($local_file, $remote_file)
    {
        //print 'get<br>';
        return ftp_get($this->ConnId, $local_file, $remote_file, FTP_BINARY); 
    }

    private function Lists($folder)
    {    
        //print 'lists<br>';
        return ftp_nlist($this->ConnId, $folder);
    }
     
    private function Delete($remode_file)
    {
        return @ftp_delete($this->ConnId, $remode_file);
    }    
    
    private function Mkdir($folder)
    {
        return @ftp_mkdir($this->ConnId, $folder);
    }
    
    private function Close()
    {
        if($this->ConnId)
            ftp_close($this->ConnId);
    }
     
    private function Upload()
    {
        $result = 0;        
        $this->ConnId = $this->Connect();       
    
        if(!$this->ConnId)
            return FTP_CONNECTION_ERROR;
            
        if(!$this->Login())
            return FTP_LOGIN_ERROR;
        
        // tutaj w tym miejscu po login
        // bardzo ważne
        $this->Passive();
        
        //wolne jak cholera
        //$this->Chdir($this->RemoteFolder);
        //$this->Mkdir(TMP_FOLDER);
        //$this->Chdir("..");
        //$this->Chdir("..");
        
        $this->Delete($this->RemoteFile);
        
        if(!$this->Put($this->LocalFile,$this->TmpFile))
            return FTP_PUT_ERROR;
    
        if(!$this->Rename($this->TmpFile,$this->RemoteFile))
            return FTP_RENAME_ERROR;
        
        return FTP_OK;
    
    }
    
    private function DownloadFolder()
    {
        $result = 0;        
        $this->ConnId = $this->Connect();       
    
        if(!$this->ConnId)
            return FTP_CONNECTION_ERROR;
            
        if(!$this->Login())
            return FTP_LOGIN_ERROR;
        
        // tutaj w tym miejscu po login
        // bardzo ważne
        //$this->Passive();

        if(!$this->Chdir($this->RemoteFolder))
            return FTP_CHDIR_ERROR;
                
        $files = $this->Lists(".");
        
        if($files == false)
            return FTP_LIST_ERROR;
        
        if(is_array($files))
        {
            foreach($files as $remote_file)
            {
		        // is file or directory ?
		        $size = ftp_size($this->ConnId,$remote_file);

		        if($size > 0)
		        {
                    $local_file = $this->LocalFolder."/".$remote_file;
                    if($this->Get($local_file, $remote_file))
                    {
                        array_push($this->RemoteFiles,$remote_file);
                        array_push($this->LocalFiles,$local_file);
                        //print $this->ArchFolder."/".$remote_file;
                        //print "\n";
                        //print $remote_file;
                        $this->Delete($this->ArchFolder."/".$remote_file);
                        $this->Rename($remote_file, $this->ArchFolder."/".$remote_file);
            
                    }
                }
	        }
        }
    
        return FTP_OK;
    
    }
    
    public function ReceiveFolder($local_folder, $remote_folder, $arch_folder)
    {
        $this->LocalFolder = $local_folder;
        $this->RemoteFolder = $remote_folder;
        $this->ArchFolder = $arch_folder;
        $result = $this->DownloadFolder();
        
        if($result != FTP_OK)
        {
            $this->Close();
            throw new \Exception("Ftp connection error (Receive) ".$result ." ".$remote_folder);
        }
        
        $this->Close();
    }
     
    // wysyłka pliku
    public function Send($local_file, $remote_file, $tmp_file, $remote_folder)
    {
        $this->LocalFile = $local_file;
        $this->RemoteFile = $remote_file;
        $this->TmpFile = $tmp_file;
        $this->RemoteFolder = $remote_folder;
        $result = $this->Upload();
        
        if($result != FTP_OK)
        {
            $this->Close();
            throw new \Exception("Ftp connection error (Send) ".$result ." ".$local_file);
        }
        
        $this->Close();
        
    }
    
}

