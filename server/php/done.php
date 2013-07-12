<?php
header('Content-type: application/json');
class Utils
{
  public static function listDirectory($dir)
  {
    $result = array();
    $root = scandir($dir);
    foreach($root as $value) {
      if($value === '.' || $value === '..') {
        continue;
      }
      if(is_file("$dir$value")) {
        $result[] = "$dir$value";
        continue;
      }
      if(is_dir("$dir$value")) {
        $result[] = "$dir$value/";
      }
      foreach(self::listDirectory("$dir$value/") as $value)
      {
        $result[] = $value;
      }
    }
    return $result;
  }
}

function rrmdir($path) {
     // Open the source directory to read in files
        $i = new DirectoryIterator($path);
        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                rrmdir($f->getRealPath());
            }
        }
        rmdir($path);
}

function compress(){
	$folder = $_GET['name'];
	$path = realpath('files/'.$folder.'/');
	$zip_file = realpath('files').'/'.$_GET['name'].'.zip';
	$file_list = Utils::listDirectory($path.'/');
	//print_r(count($file_list));
	if ($path != false){
		$zip = new ZipArchive();
		rmdir($path.'thumbnail/');
if (($zip->open($zip_file, ZIPARCHIVE::CREATE)) === true) {
    foreach ($file_list as $file) {
  
  
  //	echo "item <br/>";
    if ($file !== $zip_file) {
      $zip->addFile($file, substr($file, strlen($path)));
	//	print_r($zip);
    }
	  
  }
    $zip->close();

}
	return json_encode(array("message"=>"ok","path"=> "http://" . $_SERVER['HTTP_HOST']."/server/php/files/".$folder.'.zip'));
	}else {
	return json_encode(array("message"=>"problem"));
	}
	
	
}

echo compress();





?>	