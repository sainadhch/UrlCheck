<?php

/*
 *  Core Controller
 *	For default Controller behaviours
 */
class CoreController { 
	
	public function __call($name, $arguments)
    {
        echo '<h2>You have initiated wrong url.<h2>Please <a href="index.php?c=UrlCheck&a=urlExists">click here</a> to redirect';
    }
	
	public static function __callStatic($name, $arguments)
    {
        echo '<h2>You have initiated wrong url.<h2>Please <a href="index.php?c=UrlCheck&a=urlExists">click here</a> to redirect';
    }
	
}

?>