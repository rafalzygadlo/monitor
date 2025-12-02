<?php

/**
 * Bootstrap
 * 
 * @category   Libs
 * @package    CMS
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 
 * @copyright  2016 maxkod.pl
 * @version    1.0
 */
namespace Lib;

use Lib\Tools;
use Model\orderModel;
use Model\productModel;

class Bootstrap
{
    public $Ctrl;
    public $DefaultCtrl;       
    public $argv;
    public $Method;
    public $Param;
    public $FileName;
    public $Route;
    	
    public function Run($argv)
    {

        $this->Route = array
        (
            ['url' => 'admin/home', 'ctrl' => 'Admin/home', 'action' => 'index'],
            ['url' => 'main', 'ctrl' => 'main', 'action' => 'index']
        );


		$this->ReadArgv();
        $this->ReadGet();
        $this->CheckCtrlFile();
        $this->LoadController();
    }

    private function ReadArgv()
    {
		if(isset($this->argv))
		{
		    @list($this->FileName, $this->Ctrl, $this->Method) = $this->argv;	
		}
    
    }
	
    private function ReadGet()
    {
        // parse z URL
        if(!isset($_GET[URL]))
            return;
        
        
        
            $url = ltrim($_GET[URL],"/");
            $url = $_GET[URL];
            $array = (explode("/", $url));
            print_r($array);

            foreach($array as $folder)
            {
                $dir = ucfirst($folder);
                if(is_dir(CTRL_FOLDER . '/'. ($dir)))
                {
                    if(empty($this->Ctrl))
                        $this->Ctrl .= $dir;
                    else
                        $this->Ctrl .= '/'.$dir;
                }
                else
                {
                    if(empty($this->Ctrl))
                        $this->Ctrl .= $folder;
                    else
                        $this->Ctrl .= '/'.$folder;
                    break;
                }

            }

            $nurl = str_replace(strtolower($this->Ctrl), " ",  $url);
            //print $nurl = ltrim($nurl,"/");
            @list($this->Method) = explode("/", $nurl);

            print $this->Method;
        
        
    }

    private function CheckCtrlFile()
    {
        
        $ctrl = $this->Ctrl;
        $filename = CTRL_FOLDER . '/'. $ctrl . 'Ctrl.php';

        if (file_exists($filename) == false)
        {
            $this->LoadErrorController($filename);
        }

    }

    private function LoadController()
    {
        $ctrl = str_replace("/", "\\", $this->Ctrl); 
        $classname = CTRL_FOLDER. "\\" .$ctrl.'Ctrl';
        $class = new $classname;
        
        $action = $this->Method;
		
        if($action == null)
        {
            $class->index();
            return;
        }

		if(method_exists($class, $action))
		{
		    $class->$action();
		    
		}else{

		    
		}

    }

    private function LoadErrorController($filename)
    {
        print '404 Not Found '.$filename;
        exit;
    }


}
