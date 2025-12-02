<?php
declare(strict_types=1);

use Lib\Bootstrap;
use Lib\Tools;

// Configure error handling and logging
configureErrorHandling();

// Initialize session
initializeSession();

// Register autoloader
registerAutoloader();

// Load configuration files
loadConfigFiles();

// Initialize session security key
initializeSessionKey();

// Bootstrap and run application
bootstrapApplication();

// ============================================
// Helper Functions
// ============================================

/**
 * Configure PHP error handling settings
 */
function configureErrorHandling(): void
{
    ini_set('log_errors', true);
    ini_set('error_log', 'errors.log');
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
}

/**
 * Initialize session
 */
function initializeSession(): void
{
    session_name("monitor");
    session_start();
}

/**
 * Register PSR-4 autoloader for class loading
 */
function registerAutoloader(): void
{
    spl_autoload_extensions(".php");
    spl_autoload_register(function (string $class): void {
        $file = str_replace('\\', '/', $class) . '.php';
        require_once($file);
    });
}

/**
 * Load all required configuration files
 */
function loadConfigFiles(): void
{
    $configFiles = [
        'system.config.php',
        'Config/db.config.php'
    ];
    
    foreach ($configFiles as $file) {
        require_once($file);
    }
}

/**
 * Initialize session security key
 */
function initializeSessionKey(): void
{
    if (!isset($_SESSION["key"])) {
        $_SESSION["key"] = Tools::RandomString(16);
    }
}

/**
 * Bootstrap and run the application
 */
function bootstrapApplication(): void
{
    echo $_SESSION["key"];
    
    $app = new Bootstrap();
    $app->Run($argv ?? null);
}
