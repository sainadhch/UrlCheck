<html>
<title>Url Check</title>  
<head>

</head>  
  
<body>  
  
    <form method="post">
		<input type="text" name="user_url" placeholder="Enter url here. Ex: http://xyz.com or https://www.abc.com" style="width: 500px;" autocomplete="off" required />
		<input type="submit" value="Check Url"/>
	</form>
	
	<br/>
	<br/>
	
	<div>
		<?php echo $statusMsg; ?>
	</div>
  
</body>  
</html>  