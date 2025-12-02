<?php

/**
 * alterCtrl
 * 
 * @category   Ctrl
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2020 maxkod.pl
 * @version    1.0
 */


namespace Ctrl\Admin;

use Lib\Ctrl;

class homeCtrl extends Ctrl
{
    public function index()
    {
        print '<h1>home</h1>';
        //$alter = new \Libs\Alter();
        //$alter->run();
    }
}
