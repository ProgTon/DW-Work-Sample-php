<?php
//CAN ONLY BE CONFIGURED BEFORE ANY FILES HAS BEEN UPLOADED

//================ CONFIGURABLE VARIABLES ======================//
	$dir = 'files'; //Name of the folder which will contain the following 3 folders. 
  $unprocessedFolder = 'unprocessed'; //Name of the folder which will contain the uploaded and unprocessed CSV-files
  $processFolder = 'processing'; //Name of the folder which will contain the CSV-files during process
  $historyFolder = 'history'; //Name of the folder which will contain the CSV-files that have been saved in the database with all its information

	//To use these variables in other files, simply use the array named 'Folders', created at the bottom of this page. 
	//$folders['UnprocessedFolder'] will give you the value of the variable $UnprocessedFolder, etc. 

//==============================================//

	//DON'T TOUCH
	$unprocessedFolder = $dir.'/'.$unprocessedFolder.'/';
	$processFolder = $dir.'/'.$processFolder.'/';
	$historyFolder = $dir.'/'.$historyFolder.'/';

	function RemoveEmptySubFolders($path)
	{
		$empty=true;
		foreach (glob($path.DIRECTORY_SEPARATOR."*") as $file)
		{
			 $empty &= is_dir($file) && RemoveEmptySubFolders($file);
		}
		return $empty && @rmdir($path);
	}
	
	RemoveEmptySubFolders($dir.'/');

	if (!file_exists($dir)) mkdir($dir, 0777, true);
	if (!file_exists($unprocessedFolder)) mkdir($unprocessedFolder, 0777, true);
	if (!file_exists($processFolder)) mkdir($processFolder, 0777, true);
	if (!file_exists($historyFolder)) mkdir($historyFolder, 0777, true);

  $folders = array('UnprocessedFolder'=>$unprocessedFolder, 'ProcessFolder'=>$processFolder, 'HistoryFolder'=>$historyFolder);
?>