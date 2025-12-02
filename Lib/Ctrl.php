<?php

/**
 * Ctrl
 * 
 * @category   Ctrl
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>

 * @copyright  2018 maxkod.pl
 * @version    1.0
 */


namespace Lib;

abstract class Ctrl
{

    public $Model;
    public $Ctrl;

	public function getStatus()
	{	
		return true;
	}

}
