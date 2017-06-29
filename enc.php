<?php
function encrypt_file($source,$destination,$passphrase,$stream=NULL) {
	// $source can be a local file...
	if($stream) {
		$contents = $source;
	// OR $source can be a stream if the third argument ($stream flag) exists.
	}else{
		$handle = fopen($source, "rb");
		$contents = fread($handle, filesize($source));
		fclose($handle);
	}
 
	$iv = substr(md5("\x1B\x3C\x58".$passphrase, true), 0, 8);
	$key = substr(md5("\x2D\xFC\xD8".$passphrase, true) . md5("\x2D\xFC\xD9".$passphrase, true), 0, 24);
	$opts = array('iv'=>$iv, 'key'=>$key);
	$fp = fopen($destination, 'wb') or die("Could not open file for writing.");
	stream_filter_append($fp, 'mcrypt.tripledes', STREAM_FILTER_WRITE, $opts);
	fwrite($fp, $contents) or die("Could not write to file.");
	fclose($fp);
 
}
?>