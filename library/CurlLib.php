<?php

/*
 *  Curl Call to initiate cURL and get url response
 */
Class CurlLib {

    var $siteUrl = null;
    var $isValidUrl = false;
    var $linkAvailabilityInfo = [];

	/*
	 *	Accept url as input.
	 *	If its a valid Url, sets $siteUrl as url given by user
	 */
    public function __construct($urlFromUser = false){
        
        // Validate url from user.
		if($urlFromUser){
			$urlFromUser = strtolower($urlFromUser);
            $this->validateUrl($urlFromUser);
        
			// If a valid url.
			if($this->isValidUrl){
				$this->siteUrl = $urlFromUser;
			}
		}
    }

	/*
	 *	validateUrl
	 *	Accept url as input, validate it.
	 *	If its a valid Url, sets $isValid as true
	 */
    public function validateUrl($urlToValidate = null){
		$checkHttps   = ['http://','https://'];
		$httpExists = false;
		foreach($checkHttps as $_http){
			$findHttp = strpos($urlToValidate, $_http);
			if ($findHttp !== false){
				$httpExists = true;
			}
		}
		
		if(!$httpExists){
			$urlToValidate = 'http://'.$urlToValidate;
		}
		
        if (filter_var(strtolower($urlToValidate), FILTER_VALIDATE_URL)) {
            $this->isValidUrl = true;
        }
    }
    
	/*
	 *	getContent
	 *	initiate cURL call.
	 *	return cURL response.
	 */
    public function getContent() 
    { 
        $curlObject = curl_init();
        curl_setopt($curlObject, CURLOPT_URL, $this->siteUrl);
        curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObject, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curlObject, CURLOPT_TIMEOUT, 5);
        $curlResponseData = curl_exec($curlObject);
        $curlCallInfo = curl_getinfo($curlObject);
        curl_close($curlObject);
        $curlResponse = ["curlResponseData"=>$curlResponseData,"curlCallInfo"=>$curlCallInfo];
        return $curlResponse;
    }
    
	/*
	 *	getLinkAvailabilityInfo
	 *	From the cURL response Returns the request/default fields info.
	 */
    function getLinkAvailabilityInfo($curlResponse = [], $requiredInfoFields = ["url","http_code","redirect_url"]) 
	{ 
		if(count($curlResponse)){
			
			foreach($requiredInfoFields as $requiredInfo){
				$linkAvailabilityInfo[$requiredInfo] = $curlResponse['curlCallInfo'][$requiredInfo];
			}
			
			$redirectUrl = strtolower(trim($linkAvailabilityInfo["redirect_url"]));
			$givenUrl = strtolower(trim($linkAvailabilityInfo["url"]));
			$linkAvailableMsg = "Given Link Exists".((!empty($redirectUrl)) ? " but its redirecting to <a href='{$redirectUrl}' target='_blank'>{$redirectUrl}</a>":". <a href='{$givenUrl}' target='_blank'>{$givenUrl}</a>").".";
			
			$linkNotAvailableMsg = "Given Link doesn't Exists".((!empty($redirectUrl)) ? " but its redirecting to <a href='{$redirectUrl}' target='_blank'>{$redirectUrl}</a>" : " with <a href='{$givenUrl}' target='_blank'>{$givenUrl}</a>. Please check and try Again.").".";
			
			switch($linkAvailabilityInfo['http_code']){
				case 200:
				case 301:
				case 302:
					$linkAvailabilityInfo["linkAvailability"] = 1;
					$linkAvailabilityInfo["linkAvailabilityMsg"] = $linkAvailableMsg;
				break;
				default:
					$linkAvailabilityInfo["linkAvailability"] = 0;
					$linkAvailabilityInfo["linkAvailabilityMsg"] = $linkNotAvailableMsg;
				break;
			}
		}
		
		return $linkAvailabilityInfo;
		
	}
    
}
