<?php
    use Lib\Bootstrap;
    use Lib\Tools;
    
    ini_set('log_errors', true); // Error/Exception file logging engine.
    ini_set('error_log', 'errors.log'); // Logging file path
    ini_set("display_errors","on");
    error_reporting(E_ALL);
    session_name("monitor");
    session_start();
 

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
    
     if (!isset($_SESSION["key"]))
        $_SESSION["key"] = Tools::RandomString(16);
    
    print $_SESSION["key"];

    $app = new Bootstrap();
    if(isset($argv))
        $app->Run($argv);
    else
        $app->Run(null);
    
?>
    