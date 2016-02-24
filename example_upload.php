<?php
//require_once("class.QuasarAPI.php");
include("class.QuasarAPI.php");
$Quasar_API = new Quasar_API;
$Quasar_API->Settings("CLIENT_KEY","CLIENT_AUTH","https://quasar.alpha.neutronservers.com/","api/v3/index.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quasar API - Upload File</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
</head>
<body style="background-color: #FFFFFF;">
    <form action="" method="POST" enctype="multipart/form-data" id="form">
        <input type="file" name="file" id="file">
		<input type="hidden" name="option" value="upload">
        <input type="submit" name="submit" value="Submit">
		
<?php
if(isset($_POST['submit'])) {
	if($_POST['option'] == "upload") {
		$Upload = $Quasar_API->Upload();
		$Upload_json = json_decode($Upload, true);
		
		$files = $Quasar_API->Files($Upload_json['Quasar_API']['v3']['Upload']['SUCCESS']['hash']);
		$test = json_decode($files, true);
		print_r($test);
		
		$files1 = $Quasar_API->View($Upload_json['Quasar_API']['v3']['Upload']['SUCCESS']['hash']);
		$test1 = json_decode($files1, true);
		print_r($test1);
		
		$files2 = $Quasar_API->Rename($Upload_json['Quasar_API']['v3']['Upload']['SUCCESS']['hash'],"TEST");
		$test2 = json_decode($files2, true);
		print_r($test2);
		
		$files3 = $Quasar_API->Delete($Upload_json['Quasar_API']['v3']['Upload']['SUCCESS']['hash']);
		$test3 = json_decode($files3, true);
		print_r($test3);
	}
}
?>
    </form>
</body>
</html>
