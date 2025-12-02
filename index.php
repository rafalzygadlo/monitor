<?php
    
    ini_set('log_errors', true); // Error/Exception file logging engine.
    ini_set('error_log', 'errors.log'); // Logging file path
    ini_set("display_errors","on");
    error_reporting(E_ALL);
    
    // autoloader    
    spl_autoload_extensions(".php");
    spl_autoload_register
    (
        function ($class)
        {
            $file = str_replace('\\', '/', $class) . '.php';
            require_once($file);
        }
    );
    
    // begin config files
    
    include "system.config.php";
    include "Config/db.config.php";
    // end config files
    
    use Lib\Bootstrap;

    $app = new Bootstrap();
    if(isset($argv))
        $app->Run($argv);
    else
        $app->Run(null);
    
?>
    