<?php

/*
 *  UrlCheck Controller
 *	to check user given url was a url Exists or Not
 */
class UrlCheckController Extends CoreController {  
    public $curl;
	
	/*
	 *	curlAction
	 *	Accept url as POST input from user, validate it.
	 *	If its a valid Url, checks if it Exists or Not
	 */
	public function urlExistsAction()  
	{
		if (!empty($_POST))
		{  
			try{
				$userUrl = $_POST['user_url'];
				$this->curl = new CurlLib($userUrl);  
				$arrTest = $this->curl->getContent($userUrl);
				$linkAvailabilityInfo = $this->curl->getLinkAvailabilityInfo($arrTest);
				$statusMsg = $linkAvailabilityInfo['linkAvailabilityMsg'];
			} catch(Exception $e){
				$statusMsg = $e->getMessage();
			}
		} 
		else 
		{ 
			$statusMsg = "Waiting for URL Input";
		}  
		include 'views/urlcheck.php'; // include form view page
	}  
}  