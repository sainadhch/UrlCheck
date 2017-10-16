<form method="post">
	<input type="text" name="user_url" placeholder="Enter url here. Ex: http://xyz.com or https://www.abc.com" style="width: 500px;" autocomplete="off"/>
	<input type="submit" value="Check Url"/>
</form>
	
<br/>
<br/>

<div>
<?php 

if (!empty($_POST)){
	try{
		$urlFromUser = $_POST['user_url'];
		$isValidUrl = false;
		// Validate url from user.
        if($urlFromUser){
			$urlFromUser = strtolower($urlFromUser);
            $checkHttps   = ['http://','https://'];
			$httpExists = false;
			foreach($checkHttps as $_http){
				$findHttp = strpos($urlFromUser, $_http);
				if ($findHttp !== false){
					$httpExists = true;
				}
			}
			
			if(!$httpExists){
				$urlFromUser = 'http://'.$urlFromUser;
			}
			
			if (filter_var(strtolower($urlFromUser), FILTER_VALIDATE_URL)) {
				$isValidUrl = true;
			}
		}
		if($isValidUrl){
			$curlObject = curl_init();
			curl_setopt($curlObject, CURLOPT_URL, $urlFromUser);
			curl_setopt($curlObject, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlObject, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curlObject, CURLOPT_TIMEOUT, 5);
			$curlResponseData = curl_exec($curlObject);
			$curlCallInfo = curl_getinfo($curlObject);
			curl_close($curlObject);
			$curlResponse = ["curlResponseData"=>$curlResponseData,"curlCallInfo"=>$curlCallInfo];
			
			if(count($curlResponse)){
				$requiredInfoFields = ["url","http_code","redirect_url"];
				
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
			echo $linkAvailabilityInfo['linkAvailabilityMsg'];
		} else {
			echo "Invalid Input";
		}
	} catch(Exception $e){
		echo $e->getMessage();
	}
}
else { 
	echo "Waiting for URL Input";
} 

?>

</div>