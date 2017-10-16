<?php

/*
 *	Core
 *	initiate, autoloads Classes as requested
 */
class Core {


	public static function run() {

		self::init();

		self::autoload();

		self::dispatch();

	}
   
	public static function init() {
   
		define("DS", DIRECTORY_SEPARATOR);
		
		define("ROOT", getcwd() . DS);
		
		define("CONTROLLER_PATH", "controllers" . DS);
		
		define("MODEL_PATH", "models" . DS);

		define("VIEW_PATH", "views" . DS);
		
		define("VIEW_LIBRARY", "library" . DS);
		
		define("CORE_PATH", "core" . DS);

		// defining default controller as UrlCheck instead of Index to avoid of skipping naming convensions
		define("CONTROLLER", isset($_REQUEST['c']) ? $_REQUEST['c'] : 'UrlCheck');

		// defining default action as urlExists instead of index to avoid of skipping naming convensions
		define("ACTION", isset($_REQUEST['a']) ? $_REQUEST['a'] : 'urlExists');

		define("CURR_CONTROLLER_PATH", CONTROLLER_PATH);

		define("CURR_VIEW_PATH", VIEW_PATH);

	}

	public static function load($classname){

		if (substr($classname, -10) == "Controller"){

			// Controller
			require_once CURR_CONTROLLER_PATH . $classname . ".php";

		} elseif (substr($classname, -5) == "Model"){

			// Model
			require_once  MODEL_PATH . $classname .".php";

		} elseif (substr($classname, -3) == "Lib"){

			// Library
			require_once  VIEW_LIBRARY . $classname .".php";

		}

	}
	
	public static function autoload() {

		try{
			spl_autoload_register(array(__CLASS__,'load'));
		} catch(Exception $e) {
			throw new Exception('Error: '.$e->getMessage());
		}
	
	}

	private static function dispatch(){

		// Instantiate the controller class and call its action method

		$controller_name = CONTROLLER . "Controller";

		$action_name = ACTION . "Action";

		try{
			$controller = new $controller_name;
			$controller->$action_name();
		} catch(Exception $e){
			throw new Exception('<h2>You have initiated wrong url.<h2>Please <a href="index.php?c=UrlCheck&a=urlExists">click here</a> to redirect');
		}

	}

}
