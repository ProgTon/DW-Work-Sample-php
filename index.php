<!DOCTYPE html>
<?php include'includes/allpages.php' ?>
<html>
<head>
	<?php include'includes/head.php' ?>
	<title>Transactions</title>
</head>
<body>
	<?php
	/* ============== DEV TEST START =============== */
	// Write ?reset in the URL to delete all files and transactions.
	$query = $_SERVER['QUERY_STRING'];
	echo $query;
	if($query == 'reset'){
		$visitor->reset('everything');
		foreach($folders as $folder){
			$files = glob($folder.'*'); // get all file names
			foreach($files as $file){ // iterate files
		  	if(is_file($file)){
		    	unlink($file); // delete file
				}
			}
		}
	}
	/* ============== DEV TEST END =============== */
?>
<?php include'includes/header.php' ?>

	<main>
		<section id="upload" class="clickable clearfix">
			<div class="left">
				<form method="post" action="uploadfile.php" enctype="multipart/form-data" id="index">
				<h2>Upload CSV-file</h2>
				<input type="file" id="uploadInput" class="clicked" name="file[]" multiple value="1000000000">
				<h3 id="filesStatus">Click the background<br> to choose file(s)</h3>
				<input type="submit" id="uploadFile" value="Send">
				</form>
			</div>
			<div class="right">
				<h2>Files chosen: <span id="filesChosen">0</span> (max 15)</h2>
				<ul>
				</ul>
			</div>
		</section>
		<section class="clickable href" data-href="uploadedfiles.php">
			<h2>Manage uploaded files</h2>
		</section>
		<section class="clickable href" data-href="transactions.php">
			<h2>View transactions</h2>
		</section>
	</main>

<?php include'includes/foot.php' ?>
</body>
</html>
