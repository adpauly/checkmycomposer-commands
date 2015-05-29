<?php

class TestClass
{
	function PostToHost($host, $port, $path, $postdata, $filedata) {
	     $data = "";
	     $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
	     $fp = fsockopen($host, $port);
	     var_dump($fp); die;

	     fputs($fp, "POST $path HTTP/1.0\n");
	     fputs($fp, "Host: $host\n");
	     fputs($fp, "Content-type: multipart/form-data; boundary=".$boundary."\n");

	     // Ab dieser Stelle sammeln wir erstmal alle Daten in einem String
	     // Sammeln der POST Daten
	     foreach($postdata as $key => $val){
	         $data .= "--$boundary\n";
	         $data .= "Content-Disposition: form-data; name=\"".$key."\"\n\n".$val."\n";
	     }
	     $data .= "--$boundary\n";

	     // Sammeln der FILE Daten
	     $data .= "Content-Disposition: form-data; name=\"{$filedata[0]}\"; filename=\"{$filedata[1]}\"\n";
	     $data .= "Content-Type: image/jpeg\n";
	     $data .= "Content-Transfer-Encoding: binary\n\n";
	     $data .= $filedata[2]."\n";
	     $data .= "--$boundary--\n";

	     // Senden aller Informationen
	     fputs($fp, "Content-length: ".strlen($data)."\n\n");
	     fputs($fp, $data);

	     // Auslesen der Antwort
	     while(!feof($fp)) {
	         $res .= fread($fp, 1);
	     }
	     fclose($fp);

	     return $res;
	}

	public static function uploadWithCurl()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://www.adrienpauly.com/test.php");
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		        'file' => '@composer.json',
		));
		$result = curl_exec($ch);
		curl_close($ch);

		var_dump($result);
	}
}

$upload = new TestClass();

$postdata = array('var1'=>'test', 'var2'=>'test');
$data = file_get_contents('./composer.json');
$filedata = array('inputname', 'filename.jpg', $data);

//$upload->uploadWithCurl();
echo $upload->PostToHost("www.adrienpauly.com", 80, "/test.php", $postdata, $filedata);


