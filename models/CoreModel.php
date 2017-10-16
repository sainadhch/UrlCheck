<?php

/*
 *  Core Model
 *	For default Model behaviours
 */
class CoreModel { 
	
	public function __call($name, $arguments)
    {
        echo '<h2>Oop's..! Seems like something is missing.<h2>Please <a href="index.php?c=UrlCheck&a=urlExists">click here</a> to redirect';
    }
	
	public static function __callStatic($name, $arguments)
    {
        echo '<h2>Oop's..! Seems like something is missing.<h2>Please <a href="index.php?c=UrlCheck&a=urlExists">click here</a> to redirect';
    }
	
}

?>